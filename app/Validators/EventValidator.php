<?php

namespace App\Validators;

use App\Exceptions\Validator\EventValidatorException;
use App\Interfaces\ValidatorInterface;
use DateTime;

class EventValidator extends BaseValidator implements ValidatorInterface
{

    function validate(array $values): bool
    {
        return
            $this->validateName($values['name'], 'name') &&
            $this->validateDateTime($values['date']) &&
            $this->validateLocation($values['location']) &&
            $this->validateDescription($values['description']) &&
            $this->validateMaxAttendees($values['max']) &&
            $this->validateFileType($values['image']);
    }

    private function validateDateTime($date): bool
    {
        $currentDateTime = new DateTime();
        $userDateTime = new DateTime($date);

        if ($userDateTime > $currentDateTime) return true;
        throw new EventValidatorException('Invalid date');
    }

    private function validateLocation(string $location): bool
    {
        if (!$this->isEmpty($location) && preg_match("/^[a-zA-Z0-9-' ]*$/", $location)) return true;
        throw new EventValidatorException('Invalid location');
    }

    private function validateDescription(string $description): bool
    {
        if (preg_match("/^[a-zA-Z0-9-' ]*$/", $description)) return true;
        throw new EventValidatorException('Invalid description');
    }

    private function validateMaxAttendees(string $max): bool
    {
        if ($max >= 1) return true;
        throw new EventValidatorException('Invalid max attendees');
    }

    private function validateFileType(string $filepath): bool
    {
        $fileType = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        if ($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'png' || $filepath == '/public/Uploads/') return true;
        throw new EventValidatorException('Invalid file. File must be an image (.jpg, .jpeg, .png)');
    }
}