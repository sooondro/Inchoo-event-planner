<?php

namespace App\Interfaces;

interface ValidatorInterface {
    static function validate(string $value): bool;
}