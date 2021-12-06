<?php

namespace App\Validators;

use App\Exceptions\Validator\NameValidatorException;
use App\Interfaces\ValidatorInterface;

class NameValidator implements ValidatorInterface
{

    /**
     * @throws NameValidatorException
     */
    public static function validate(string $value): bool
    {
        if (!self::isEmpty($value) && self::matchesRegex($value)) return true;
        throw new NameValidatorException('Invalid name');
    }

    private static function isEmpty(string $name): bool
    {
        if (empty($name)) return true;
        return false;
    }

    private static function matchesRegex(string $name): bool
    {
        if (preg_match("/^[a-zA-Z-' ]*$/", $name)) return true;
        return false;
    }
}