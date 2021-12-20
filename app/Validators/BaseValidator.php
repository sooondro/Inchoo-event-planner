<?php

namespace App\Validators;

use App\Exceptions\Validator\BaseValidatorException;

class BaseValidator
{
    protected function isEmpty(string $value): bool
    {
        return empty($value);
    }

    protected function validateName(string $value, string $parameter): bool {
        if (!$this->isEmpty($value) && $this->isOnlyLettersApostrophesDashesAndWhitespaces($value)) return true;
        throw new BaseValidatorException('Invalid ' . $parameter);
    }

    protected function isOnlyLettersApostrophesDashesAndWhitespaces(string $value)
    {
        return preg_match("/^[a-zA-Z-' ]*$/", $value);
    }
}