<?php

namespace App\Validators;

use App\Exceptions\Validator\LocationValidatorException;
use App\Interfaces\ValidatorInterface;

class LocationValidator implements ValidatorInterface
{

    public static function validate(string $value): bool
    {
        if (!self::isEmpty($value) && self::matchesRegex($value)) return true;
        throw new LocationValidatorException('Invalid text in location');
    }

    private static function isEmpty(string $location): bool
    {
        if (empty($location)) return true;
        return false;
    }

    private static function matchesRegex(string $location): bool
    {
        if (preg_match("/^[0-9a-zA-Z-' ]*$/", $location)) return true;
        return false;
    }
}