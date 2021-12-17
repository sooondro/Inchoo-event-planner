<?php

namespace App\Controllers;

use App\Models\User;
use PDO;

class LoginController extends AbstractController
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
     * Serves as a handle function for '/login' uri
     * Checks if the request is GET or POST and calls adequate handle function
     * @param $response
     * @return void
     */
    public function index($response)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prepareUserInput();
            return $this->handlePostRequest($response);
        }
        return $this->handleGetRequest($response);
    }

    /**
     * GET request handle function, renders login page
     * If the user is already logged in, redirects to homepage
     * @param $response
     * @return void
     */
    private function handleGetRequest($response)
    {
        if ($this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('login', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName()
        ]));
    }

    /**
     * POST request handle function
     * Validates user credentials, then creates a session and stores user id
     * If validation fails, redirect back to login page and displays error message
     * @param $response
     * @return void
     */
    private function handlePostRequest($response)
    {
        if ($this->validateUserCredentials()) {
            $this->startSessionAndStoreUserId($this->getUserByEmail()->id);
            header('Location: /');
            die();

        }
        return $response->setBody($response->renderView('login', [
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'formValues' => $this->formValues,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName()
        ]));
    }

    /**
     * Prepares user input in POST request
     * @return void
     */
    private function prepareUserInput()
    {
        $this->formValues = $this->fetchFormValuesAsArray();
    }


    /**
     * Validates user credentials
     * First tries to get user by email, if no user is found, validation fails
     * Secondly, if user is found, checks if password matches the pass stored in db
     * returns true if user exists and the password is correct
     * @return bool
     */
    private function validateUserCredentials(): bool
    {
        $user = $this->getUserByEmail();
        if (!$user || !$this->verifyPassword($this->formValues['password'], $user->password)) {
            $this->errMessage = 'Invalid email or password';
            return false;
        }
        return true;
    }

    /**
     * Returns user found in the db by email
     * Returns false if no user is found with the given email adress
     * @return false|mixed
     */
    private function getUserByEmail()
    {
        $email = $_POST['email'];
        $user = User::findUserByEmail($this->db, $email);
        if ($user) {
            return $user;
        }
        return false;
    }

    /**
     * Checks if the password is same as the hashed password stored in the db
     * Returns true if passwords match
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     */
    private function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Fetches all form data and stores it in associative array
     * @return array
     */
    private function fetchFormValuesAsArray(): array
    {
        $values = [];
        $values['email'] = $_POST['email'];
        $values['password'] = $_POST['password'];
        return $values;
    }

    /**
     * Starts a session if no session is started and stores userId in session
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