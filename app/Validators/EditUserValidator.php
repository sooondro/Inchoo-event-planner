<?php

namespace App\Validators;

use App\Exceptions\Validator\EditUserValidatorException;
use App\Interfaces\ValidatorInterface;

class EditUserValidator extends BaseValidator implements ValidatorInterface
{

    function validate(array $values): bool
    {
        return
            $this->validateEmail($values['email']) &&
            $this->validateName($values['name'], 'name') &&
            $this->validateName($values['surname'], 'surname');
    }


    private function validateEmail(string $email): bool
    {
        if (!$this->isEmpty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        throw new EditUserValidatorException('Invalid email');
    }
}