<?php

namespace App\Validators;

use App\Exceptions\Validator\BaseValidatorException;
use App\Exceptions\Validator\SignupValidatorException;
use App\Interfaces\ValidatorInterface;

/**
 * Used to validate signup form.
 */
class SignupValidator extends BaseValidator implements ValidatorInterface
{

    /**
     * Validates given signup values.
     * @param array $values
     * @return bool
     * @throws SignupValidatorException
     * @throws BaseValidatorException
     */
    public function validate(array $values): bool
    {
        return
            $this->validateEmail($values['email']) &&
            $this->validateName($values['name'], 'name') &&
            $this->validateName($values['surname'], 'surname') &&
            $this->validatePassword($values['password']);
    }

    /**
     * Validates if email is valid.
     * @param string $email
     * @return bool
     * @throws SignupValidatorException
     */
    private function validateEmail(string $email): bool
    {
        if (!$this->isEmpty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        throw new SignupValidatorException('Invalid email');
    }

    /**
     * Validates if password value has more than 5 characters.
     * @param string $password
     * @return bool
     * @throws SignupValidatorException
     */
    private function validatePassword(string $password): bool
    {
        if (strlen($password) > 5) return true;
        throw new SignupValidatorException('Invalid password');
    }


}