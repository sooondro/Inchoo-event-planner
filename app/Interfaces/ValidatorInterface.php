<?php

namespace App\Interfaces;

interface ValidatorInterface {
    function validate(array $values): bool;
}