<?php

namespace App\Validators;

use App\Exceptions\Validator\MaxAttendeesValidatorException;
use App\Interfaces\ValidatorInterface;

class MaxAttendeesValidator implements ValidatorInterface
{

    static function validate(string $value): bool
    {
        if ($value < 1) throw new MaxAttendeesValidatorException(
            'Max attendees number has to be positive'
        );
        return true;
    }
}