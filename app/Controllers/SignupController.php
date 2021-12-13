<?php

namespace App\Controllers;

use App\Models\User;
use App\Validators\EmailValidator;
use App\Validators\NameValidator;
use App\Validators\PasswordValidator;
use App\Validators\SurnameValidator;
use PDO;

class SignupController extends AbstractController
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
     * Serves as handle function for '/signup' uri
     * If user is logged in, redirects to homepage
     * Checks if the request is POST or GET and calls adequate function
     * @param $response
     * @return void
     */
    public function index($response)
    {
        if ($this->authController->isLoggedIn()) {
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
     * Serves as handle function for '/create-amin' uri
     * If the current user is not and admin, redirects to homepage
     * Checks if the request is POST or GET and calls adequate function
     * @param $response
     * @return void
     */
    public function createAdmin($response) {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prepareUserInput();
            return $this->handlePostRequestCreateAdmin($response);
        }
        return $this->handleGetRequest($response);
    }

    /**
     * GET request handle function
     * Used for normal signup and creation of admin account
     * If the current user is admin, the location for form action is /create-admin
     * Otherwise, the location is /signup
     * @param $response
     * @return mixed
     */
    private function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('signup', [
            'location' => $this->authController->isAdmin() ? '/create-admin' : '/signup',
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    /**
     * POST request handle function for create-admin
     * Validated user input, if successful, creates new admin and redirects to homepage
     * If validation fails, redirects to signup page and displays error message
     * @param $response
     * @return void
     */
    private function handlePostRequestCreateAdmin($response)
    {
        if ($this->validateUserInput()) {
            $id =User::signUpAdminUser($this->db, $this->formValues);
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('signup', [
            'location' => '/create-admin',
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    /**
     * POST request handle function for /signup
     * If validation is successful, creates new user and logs him in
     * Otherwise, redirects to signup page and displays error message
     * @param $response
     * @return void
     */
    private function handlePostRequest($response)
    {
        if ($this->validateUserInput()) {
            $id = User::signUpUser($this->db, $this->formValues);
            $this->startSessionAndStoreUserId($id);
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('signup', [
            'location' => '/signup',
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    /**
     * Calls all necessary functions for preparing user input data
     * @return void
     */
    private function prepareUserInput()
    {
        $this->formValues = $this->fetchFormValuesAsArray();
        $this->trimAllWhitespaceFromUserInput();
        $this->uppercaseFirstLetterOfNameAndSurname();
    }

    /**
     * Turns first letters of name and surname to capital letters
     * @return void
     */
    private function uppercaseFirstLetterOfNameAndSurname()
    {
        $this->formValues['name'] = ucfirst($this->formValues['name']);
        $this->formValues['surname'] = ucfirst($this->formValues['surname']);
    }

    /**
     * Trims all unnecessary whitespace in user input
     * @return void
     */
    private function trimAllWhitespaceFromUserInput()
    {
        $this->formValues['name'] = trim($this->formValues['name']);
        $this->formValues['surname'] = trim($this->formValues['surname']);
        $this->formValues['email'] = trim($this->formValues['email']);
    }

    /**
     * Returns an associative array of user input data or better data handling
     * @return array
     */
    private function fetchFormValuesAsArray(): array
    {
        $values = [];
        $values['name'] = $_POST['name'];
        $values['surname'] = $_POST['surname'];
        $values['email'] = $_POST['email'];
        $values['password'] = $this->hashPassword();
        return $values;
    }

    /**
     * Validates user input
     * If validation passes, returns true
     * @return bool
     */
    private function validateUserInput(): bool
    {
        try {
            if (
                PasswordValidator::validate($_POST['password'])
                && NameValidator::validate($this->formValues['name'])
                && SurnameValidator::validate($this->formValues['surname'])
                && EmailValidator::validate($this->formValues['email'])
                && !$this->userExists()
                && $this->passwordsMatch()
            ) return true;
        } catch (\Exception $e) {
            $this->errMessage = $e->getMessage();
        };
        return false;
    }

    /**
     * Hashes user password for storing in database
     * Returns the hashes password
     * @return string
     */
    private function hashPassword(): string
    {
        $password = $_POST['password'];
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    /**
     * Checks if the password and the repeated password match
     * @return bool
     */
    private function passwordsMatch(): bool
    {
        if ($_POST['password'] !== $_POST['repeated-password']) {
            $this->errMessage = 'Passwords have to match';
            return false;
        }
        return true;
    }

    /**
     * Checks if a user with given email already exists
     * Returns true if user exists
     * @return bool
     */
    private function userExists(): bool
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
     * Starts a session and stores user id in session
     * @param $id
     * @return void
     */
    private function startSessionAndStoreUserId($id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['userId'] = $id;
    }

}