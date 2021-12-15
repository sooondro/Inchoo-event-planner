<?php

namespace App\Controllers;

use App\Models\Event;
use App\Validators\DateTimeValidator;
use App\Validators\DescriptionValidator;
use App\Validators\EventValidator;
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

    /**
     * Serves as a handler for '/create-event' uri
     * Redirects to homepage if user does not have admin privilege
     * If the request is GET request, calls GET request handler
     * If the request is POST request, prepares POST data and calls POST request handler
     * @param $response
     * @return void
     */
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

    /**
     * Serves as a handler for '/delete-event' uri
     * If current user is not admin, redirects to homepage
     * Deletes event and redirects to location from which it has been called
     * @param $response
     * @return void
     */
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

    /**
     * Serves as a handler for 'edit-event' uri
     * If the user is not admin, redirects to homepage
     * If the request is GET request, calls GET request handler
     * If the request is POST request, prepares POST data and calls POST request handler
     * @param $response
     * @return void
     */
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

    /**
     * GET request handler, redirects to create-event view with location create-event
     * @param $response
     * @return mixed
     */
    private function handleGetRequestCreateEvent($response)
    {
        return $response->setBody($response->renderView('event-form', [
            'location' => '/create-event',
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    /**
     *POST request handle function
     * validates form input
     * if validation passes, creates new event
     * if validation does not pass, redirects user back to create event form and displays error message
     * @param $response
     * @return void
     */
    private function handlePostRequestCreateEvent($response)
    {
        if ($this->validateUserInput()) {
            Event::postNewEvent($this->db, $this->formValues);
            $this->uploadFile();
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('event-form', [
            'location' => '/create-event',
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    /**
     * GET request edit event handle function
     * fetches event that needs update by his id and prepopulates form values
     * @param $response
     * @return mixed
     */
    private function handleGetRequestEditEvent($response)
    {
        $eventId = $_GET['eventId'];
        $event = Event::fetchEventById($this->db, $eventId);
        $this->setFormValuesFromFetchedEvent($event);
        return $response->setBody($response->renderView('event-form', [
            'location' => '/edit-event',
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    /**
     * POST request edit event handle function
     * Validates user input
     * if user input is valid, updates the chosen event
     * if validation fails, redirects back to the form and displays error message
     * @param $response
     * @return void
     */
    private function handlePostRequestEditEvent($response)
    {
        if ($this->validateUserInput()) {
            Event::updateAdminEvent($this->db, $this->formValues);
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('event-form', [
            'location' => '/edit-event',
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    /**
     * Calls all necessary function for preparing user input
     * @return void
     */
    private function prepareUserInput()
    {
        $this->formValues = $this->fetchFormValuesAsArray();
        $this->trimAllWhitespaceFromUserInput();

    }

    /**
     * Responsible for validating user input using Validators
     * @return bool
     */
    private function validateUserInput(): bool
    {
        $validator = new EventValidator();
        try {
            return $validator->validate($this->formValues);
        } catch (\Exception $e) {
            $this->errMessage = $e->getMessage();
        };
        return false;
    }

    /**
     * returns all user form data as an associative array
     * Used for easier handling of form data
     * @return array
     */
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
        $values['image'] = $this->fetchImagePath();
        return $values;
    }

    /**
     * Trims all unnecessary white spaces from user input fields
     * @return void
     */
    private function trimAllWhitespaceFromUserInput()
    {
        $this->formValues['name'] = trim($this->formValues['name']);
        $this->formValues['location'] = trim($this->formValues['location']);
        $this->formValues['description'] = trim($this->formValues['description']);
    }

    private function uploadFile(){
        $uploaddir = '/var/www/event-planner/app/Uploads/';
        $uploadFile = $uploaddir . basename($_FILES['image']['name']);

        echo '<pre>';
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n";
        }
    }

    private function fetchImagePath(): string
    {
        $uploaddir = '/app/Uploads/';
        return $uploaddir . basename($_FILES['image']['name']);
    }

    /**
     * Used for edit event prepopulation.
     * Created so the controller can work with create event and edit event requests with same functions
     * @param $event
     * @return void
     */
    private function setFormValuesFromFetchedEvent($event){
        $this->formValues['name'] = $event->name;
        $this->formValues['location'] = $event->location;
        $this->formValues['max'] = $event->max_attendees;
        $this->formValues['description'] = $event->description;
        $this->formValues['date'] = date('Y-m-d\TH:i', strtotime($event->date));
        $this->formValues['eventId'] = $event->id;
        $this->formValues['image'] = $event->image;
    }


}
