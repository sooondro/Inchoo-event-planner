<?php

namespace App\Validators;

use App\Exceptions\Validator\SignupValidatorException;
use App\Interfaces\ValidatorInterface;

class SignupValidator extends BaseValidator implements ValidatorInterface
{

    public function validate(array $values): bool
    {

            return
                $this->validateEmail($values['email']) &&
                $this->validateName($values['name']) &&
                $this->validateSurname($values['surname']) &&
                $this->validatePassword($values['password']);

    }

    private function validateEmail(string $email): bool
    {
        if (!$this->isEmpty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        throw new SignupValidatorException('Invalid email');
    }

    private function validateName(string $name): bool
    {
        if (!$this->isEmpty($name) && $this->isOnlyLettersApostrophesDashesAndWhitespaces($name)) return true;
        throw new SignupValidatorException('Invalid name');
    }

    private function validateSurname(string $surname): bool
    {
        if (!$this->isEmpty($surname) && $this->isOnlyLettersApostrophesDashesAndWhitespaces($surname)) return true;
        throw new SignupValidatorException('Invalid surname');
    }

    private function validatePassword(string $password): bool
    {
        if (strlen($password) > 5) return true;
        throw new SignupValidatorException('Invalid password');
    }

    private function isOnlyLettersApostrophesDashesAndWhitespaces(string $value)
    {
        return preg_match("/^[a-zA-Z-' ]*$/", $value);
    }
}