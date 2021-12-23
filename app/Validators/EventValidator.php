<?php

namespace App\Validators;

use App\Exceptions\Validator\BaseValidatorException;
use App\Exceptions\Validator\EventValidatorException;
use App\Interfaces\ValidatorInterface;
use DateTime;
use Exception;

/**
 * Used to validate event form.
 */
class EventValidator extends BaseValidator implements ValidatorInterface
{

    /**
     * Validates given event values.
     * @param array $values
     * @return bool
     * @throws EventValidatorException
     * @throws BaseValidatorException
     */
    function validate(array $values): bool
    {
        return
            $this->validateEventName($values['name']) &&
            $this->validateDateTime($values['date']) &&
            $this->validateLocation($values['location']) &&
            $this->validateDescription($values['description']) &&
            $this->validateMaxAttendees($values['max']) &&
            $this->validateFileType($values['image']);
    }

    /**
     * Validates if given DateTime is in the future.
     * @param $date
     * @return bool
     * @throws EventValidatorException
     * @throws Exception
     */
    private function validateDateTime($date): bool
    {
        $currentDateTime = new DateTime();
        $userDateTime = new DateTime($date);

        if ($userDateTime > $currentDateTime) return true;
        throw new EventValidatorException('Invalid date');
    }

    /**
     * Validates if location value is valid.
     * @param string $location
     * @return bool
     * @throws EventValidatorException
     */
    private function validateEventName(string $location): bool
    {
        if (!$this->isEmpty($location) && preg_match("/^[a-zA-Z0-9-'!,. ]*$/", $location)) return true;
        throw new EventValidatorException('Invalid name');
    }

    /**
     * Validates if location value is valid.
     * @param string $location
     * @return bool
     * @throws EventValidatorException
     */
    private function validateLocation(string $location): bool
    {
        if (!$this->isEmpty($location) && preg_match("/^[a-zA-Z0-9-'., ]*$/", $location)) return true;
        throw new EventValidatorException('Invalid location');
    }

    /**
     * Validates if description value is valid.
     * @param string $description
     * @return bool
     * @throws EventValidatorException
     */
    private function validateDescription(string $description): bool
    {
        if (!$this->isEmpty($description)) return true;
        throw new EventValidatorException('Invalid description');
    }

    /**
     * Validates if max value is bigger or even to 1.
     * @param string $max
     * @return bool
     * @throws EventValidatorException
     */
    private function validateMaxAttendees(string $max): bool
    {
        if ($max >= 1) return true;
        throw new EventValidatorException('Invalid max attendees');
    }

    /**
     * Validates if given filepath has correct extension.
     * @param string $filepath
     * @return bool
     * @throws EventValidatorException
     */
    private function validateFileType(string $filepath): bool
    {
        $fileType = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        if ($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'png' || $filepath == '/public/Uploads/') return true;
        throw new EventValidatorException('Invalid file. File must be an image (.jpg, .jpeg, .png)');
    }
}