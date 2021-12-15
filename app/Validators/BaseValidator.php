<?php

namespace App\Validators;

class BaseValidator
{
    protected function isEmpty(string $value): bool
    {
        return empty($value);
    }
}