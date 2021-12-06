<?php

namespace App\Validators;

use App\Exceptions\Validator\PasswordValidatorException;
use App\Interfaces\ValidatorInterface;

class PasswordValidator implements ValidatorInterface
{

    static function validate(string $value): bool
    {
        if (self::isLengthGreater($value)) return true;
        throw new PasswordValidatorException('Invalid password');
    }

    private static function isLengthGreater(string $password): bool
    {
        if (strlen($password) > 5) return true;
        return false;
    }
}
