<?php

namespace App\Validators;

use App\Exceptions\Validator\SurnameValidatorException;
use App\Interfaces\ValidatorInterface;

class SurnameValidator implements ValidatorInterface
{

    public static function validate(string $value): bool
    {
        if (!self::isEmpty($value) && self::matchesRegex($value)) return true;
        throw new SurnameValidatorException('Invalid surname');
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