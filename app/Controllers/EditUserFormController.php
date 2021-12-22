<?php

namespace App\Controllers;

use App\Models\User;
use App\Response;
use App\Validators\EditUserValidator;
use Exception;
use PDO;

class EditUserFormController extends AbstractController
{
    private $formValues = [];
    private $errMessage = '';
    protected $db;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    /**
     * Serves as a handler function for '/edit-user' uri.
     * Checks if user is logged in.
     * Checks request method and calls adequate handler functions.
     * If request method is POST, prepares user input.
     * @param Response $response
     * @return Response|void
     */
    public function index(Response $response)
    {
        if (!$this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prepareUserInput();
            return $this->handlePostRequest($response);
        }

        return $this->handleGetRequest($response);
    }

    /**
     * GET request handler function. Renders edit-user view.
     * @param Response $response
     * @return Response
     */
    private function handleGetRequest(Response $response): Response
    {
        return $response->setBody($response->renderView('edit-user', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName(),
            'user' => $this->authController->getCurrentUser()
        ]));
    }

    /**
     * POST request handler function.
     * Validates user input, if it fails, rerenders edit-user view with error message displayed.
     * Otherwise, edits user info in database.
     * @param Response $response
     * @return Response|void
     */
    private function handlePostRequest(Response $response)
    {
        if ($this->validateUserInput()) {
            User::editUser($this->db, $this->formValues);
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('edit-user', [
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'user' => $this->authController->getCurrentUser(),
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName()
        ]));
    }

    /**
     * Prepares user form input.
     */
    private function prepareUserInput()
    {
        $this->formValues = $this->fetchFormValuesAsArray();
        $this->trimAllWhitespaceFromUserInput();
    }

    /**
     * Returns an associative array of user form inputs.
     * @return array
     */
    private function fetchFormValuesAsArray(): array
    {
        $values = [];
        $values['name'] = $_POST['name'];
        $values['surname'] = $_POST['surname'];
        $values['email'] = $_POST['email'];
        $values['password'] = $_POST['password'];
        $values['id'] = $this->authController->getActiveUserId();
        return $values;
    }

    /**
     * Trims all unnecessary whitespace from user input.
     */
    private function trimAllWhitespaceFromUserInput()
    {
        $this->formValues['name'] = trim($this->formValues['name']);
        $this->formValues['surname'] = trim($this->formValues['surname']);
        $this->formValues['email'] = trim($this->formValues['email']);
    }

    /**
     * Validates user input with EditUserValidator. Returns true if validation passes.
     * @return bool
     */
    private function validateUserInput(): bool
    {
        $validator = new EditUserValidator();
        try {
            if (
                $this->authController->getActiveUserEmail() !== $this->formValues['email'] &&
                $this->userEmailExists()
            ) return false;
            if (
                $this->verifyPassword($this->formValues['password'], $this->authController->getCurrentUserPassword()) &&
                $validator->validate($this->formValues)
            ) return true;
        } catch (Exception $e) {
            $this->errMessage = $e->getMessage();
        }
        return false;
    }

    /**
     * Checks if a user with given email already exists.
     * @return bool
     */
    private function userEmailExists(): bool
    {
        $email = $this->formValues['email'];
        $user = User::findUserByEmail($this->db, $email);
        if ($user) {
            $this->errMessage = 'User with specified email already exists';
            return true;
        }
        return false;
    }

    /**
     * Checks whether given password and active user hashed passwords match.
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     * @throws Exception
     */
    private function verifyPassword(string $password, string $hashedPassword): bool
    {
        if (password_verify($password, $hashedPassword)) return true;
        throw new Exception('Invalid password');
    }

}