<?php

namespace App\Validators;

use App\Exceptions\Validator\SignupValidatorException;

class BaseValidator
{
    protected function isEmpty(string $value): bool
    {
        return empty($value);
    }

    protected function validateName(string $value, string $parameter): bool {
        if (!$this->isEmpty($value) && $this->isOnlyLettersApostrophesDashesAndWhitespaces($value)) return true;
        throw new SignupValidatorException('Invalid ' . $parameter);
    }

    protected function isOnlyLettersApostrophesDashesAndWhitespaces(string $value)
    {
        return preg_match("/^[a-zA-Z-' ]*$/", $value);
    }
}