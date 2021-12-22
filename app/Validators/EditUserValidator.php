<?php

namespace App\Validators;

use App\Exceptions\Validator\BaseValidatorException;
use App\Exceptions\Validator\EditUserValidatorException;
use App\Interfaces\ValidatorInterface;

/**
 * Used to validate edit user form.
 */
class EditUserValidator extends BaseValidator implements ValidatorInterface
{

    /**
     * Validates edit user input values.
     * @param array $values
     * @return bool
     * @throws EditUserValidatorException
     * @throws BaseValidatorException
     */
    function validate(array $values): bool
    {
        return
            $this->validateEmail($values['email']) &&
            $this->validateName($values['name'], 'name') &&
            $this->validateName($values['surname'], 'surname');
    }

    /**
     * Validates if given email is valid.
     * @param string $email
     * @return bool
     * @throws EditUserValidatorException
     */
    private function validateEmail(string $email): bool
    {
        if (!$this->isEmpty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        throw new EditUserValidatorException('Invalid email');
    }
}