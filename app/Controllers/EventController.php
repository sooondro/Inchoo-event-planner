<?php

namespace App\Controllers;

use App\Models\Event;
use App\Validators\DateTimeValidator;
use App\Validators\DescriptionValidator;
use App\Validators\LocationValidator;
use App\Validators\MaxAttendeesValidator;
use App\Validators\NameValidator;
use DateTime;
use PDO;

class EventController extends AbstractController
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
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prepareUserInput();
            return $this->handlePostRequestCreateEvent($response);
        }
        return $this->handleGetRequestCreateEvent($response);
    }

    public function delete($response) {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }

        $eventId = $_POST['eventId'];

        Event::deleteEventById($this->db ,$eventId);
        header('Location: ' . $_POST['location']);
        die();
    }

    public function edit($response) {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prepareUserInput();
            return $this->handlePostRequestEditEvent($response);
        }
        return $this->handleGetRequestEditEvent($response);

    }

    private function handleGetRequestCreateEvent($response)
    {
        return $response->setBody($response->renderView('create-event', [
            'location' => '/create-event',
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    private function handlePostRequestCreateEvent($response)
    {
        if ($this->validateUserInput()) {
            Event::postNewEvent($this->db, $this->formValues);
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('create-event', [
            'location' => '/create-event',
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    private function handleGetRequestEditEvent($response)
    {
        $eventId = $_GET['eventId'];
        $event = Event::fetchEventById($this->db, $eventId);
        $this->setFormValuesFromFetchedEvent($event);
        return $response->setBody($response->renderView('create-event', [
            'location' => '/edit-event',
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    private function handlePostRequestEditEvent($response)
    {
        if ($this->validateUserInput()) {
            Event::updateAdminEvent($this->db, $this->formValues);
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('create-event', [
            'location' => '/edit-event',
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
                && DateTimeValidator::validate($this->formValues['date'])
                && LocationValidator::validate($this->formValues['location'])
                && MaxAttendeesValidator::validate($this->formValues['max'])
                && DescriptionValidator::validate($this->formValues['description'])
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
        $values['eventId'] = $_POST['eventId'];
        return $values;
    }

    private function trimAllWhitespaceFromUserInput()
    {
        $this->formValues['name'] = trim($this->formValues['name']);
        $this->formValues['location'] = trim($this->formValues['location']);
        $this->formValues['description'] = trim($this->formValues['description']);
    }

    private function setFormValuesFromFetchedEvent($event){
        $this->formValues['name'] = $event->name;
        $this->formValues['location'] = $event->location;
        $this->formValues['max'] = $event->max_attendees;
        $this->formValues['description'] = $event->description;
        $this->formValues['date'] = date('Y-m-d\TH:i', strtotime($event->date));
        $this->formValues['eventId'] = $event->id;
    }
}
