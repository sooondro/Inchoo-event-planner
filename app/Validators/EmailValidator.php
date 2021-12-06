<?php

namespace App\Validators;

use App\Exceptions\Validator\EmailValidatorException;
use App\Interfaces\ValidatorInterface;

class EmailValidator implements ValidatorInterface
{

    public static function validate(string $value): bool
    {
        if (self::isEmail($value) && !self::isEmpty($value)) return true;
        throw new EmailValidatorException('Invalid email');
    }

    private static function isEmpty(string $email): bool
    {
        if (empty($email)) return true;
        return false;
    }

    private static function isEmail(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        return false;
    }

/*    private static function checkIfUserExists(string $email)
    {

    }*/
}