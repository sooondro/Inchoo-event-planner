<?php

namespace App\Validators;

use App\Exceptions\Validator\DateTimeValidatorException;
use App\Interfaces\ValidatorInterface;
use DateTime;

class DateTimeValidator implements ValidatorInterface
{

    static function validate(string $value): bool
    {


        if (self::hasUserDatePast($value))
            throw new DateTimeValidatorException(
                'Date and time has to be set to future'
            );

        return true;
    }

    private static function hasUserDatePast($userDate): bool
    {
        $currentDateTime = new DateTime();
        $userDateTime = new DateTime($userDate);

        if ($userDateTime < $currentDateTime) return true;
        return false;
    }
}