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
            $this->validateName($values['name'], 'name') &&
            $this->validateName($values['surname'], 'surname') &&
            $this->validatePassword($values['password']);
    }

    private function validateEmail(string $email): bool
    {
        if (!$this->isEmpty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        throw new SignupValidatorException('Invalid email');
    }

    private function validatePassword(string $password): bool
    {
        if (strlen($password) > 5) return true;
        throw new SignupValidatorException('Invalid password');
    }


}