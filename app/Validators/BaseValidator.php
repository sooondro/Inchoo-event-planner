<?php

namespace App\Validators;

use App\Exceptions\Validator\BaseValidatorException;

class BaseValidator
{
    /**
     * Checks if the value is empty.
     * @param string $value
     * @return bool
     */
    protected function isEmpty(string $value): bool
    {
        return empty($value);
    }

    /**
     * Validates name.
     * Checks if given value is not empty and contains only name characters.
     * @param string $value
     * @param string $parameter
     * @return bool
     * @throws BaseValidatorException
     */
    protected function validateName(string $value, string $parameter): bool {
        if (!$this->isEmpty($value) && $this->isOnlyLettersApostrophesDashesAndWhitespaces($value)) return true;
        throw new BaseValidatorException('Invalid ' . $parameter);
    }

    /**
     * Checks if value contains only letters, apostrophes, dashes and whitespace by comparing regex.
     * @param string $value
     * @return false|int
     */
    protected function isOnlyLettersApostrophesDashesAndWhitespaces(string $value)
    {
        return preg_match("/^[a-zA-Z-' ]*$/", $value);
    }
}