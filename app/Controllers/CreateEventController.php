<?php

namespace App\Controllers;

use App\Models\Event;
use App\Validators\NameValidator;
use PDO;

class CreateEventController extends AbstractController
{

    protected $db;
    protected $formValues = [];
    protected $errMessage = '';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    public function index($response)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prepareUserInput();
            return $this->handlePostRequest($response);
        }
        return $this->handleGetRequest($response);
    }

    private function handleGetRequest($response)
    {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('create-event', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    private function handlePostRequest($response)
    {
        if ($this->validateUserInput()) {
            Event::postNewEvent($this->db, $this->formValues);
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('create-event', [
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    private function prepareUserInput()
    {
        $this->formValues = $this->fetchFormValuesAsArray();
        $this->trimAllWhitespaceFromUserInput();
    }

    private function validateUserInput(): bool
    {
        try {
            if (
                NameValidator::validate($this->formValues['name'])
                && SurnameValidator::validate($this->formValues['surname'])
                && DateTimeValidator::validate($this->formValues['date'])

            ) return true;
        } catch (\Exception $e) {
            $this->errMessage = $e->getMessage();
        };
        return false;
    }

    private function fetchFormValuesAsArray(): array
    {
        $values = [];
        $values['name'] = $_POST['name'];
        $values['location'] = $_POST['location'];
        $values['max'] = $_POST['max'];
        $values['description'] = $_POST['description'];
        $values['date'] = $_POST['date'];
        $values['adminId'] = $this->authController->getActiveUserId();
        return $values;
    }

    private function trimAllWhitespaceFromUserInput()
    {
        $this->formValues['name'] = trim($this->formValues['name']);
        $this->formValues['location'] = trim($this->formValues['location']);
        $this->formValues['description'] = trim($this->formValues['description']);
    }


}
