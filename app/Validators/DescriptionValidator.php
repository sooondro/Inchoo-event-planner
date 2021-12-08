<?php

namespace App\Validators;

use App\Exceptions\Validator\DescriptionValidatorException;
use App\Interfaces\ValidatorInterface;

class DescriptionValidator implements ValidatorInterface
{

    public static function validate(string $value): bool
    {
        if (!self::isEmpty($value)) return true;
        throw new DescriptionValidatorException('Invalid characters in description');
    }

    private static function isEmpty(string $description): bool
    {
        if (empty($description)) return true;
        return false;
    }

    private static function matchesRegex(string $description): bool
    {
        if (preg_match("/^[0-9a-zA-Z-'*/()<> ]*$/", $description)) return true;
        return false;
    }

}