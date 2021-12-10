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

    private function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('signup', [
            'location' => $this->authController->isAdmin() ? '/create-admin' : '/signup',
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }
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


    private function prepareUserInput()
    {
        $this->formValues = $this->fetchFormValuesAsArray();
        $this->trimAllWhitespaceFromUserInput();
        $this->uppercaseFirstLetterOfNameAndSurname();
    }

    private function uppercaseFirstLetterOfNameAndSurname()
    {
        $this->formValues['name'] = ucfirst($this->formValues['name']);
        $this->formValues['surname'] = ucfirst($this->formValues['surname']);
    }

    private function trimAllWhitespaceFromUserInput()
    {
        $this->formValues['name'] = trim($this->formValues['name']);
        $this->formValues['surname'] = trim($this->formValues['surname']);
        $this->formValues['email'] = trim($this->formValues['email']);
    }

    private function fetchFormValuesAsArray(): array
    {
        $values = [];
        $values['name'] = $_POST['name'];
        $values['surname'] = $_POST['surname'];
        $values['email'] = $_POST['email'];
        $values['password'] = $this->hashPassword();
        return $values;
    }

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

    private function hashPassword(): string
    {
        $password = $_POST['password'];
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    private function passwordsMatch(): bool
    {
        if ($_POST['password'] !== $_POST['repeated-password']) {
            $this->errMessage = 'Passwords have to match';
            return false;
        }
        return true;
    }

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

    private function startSessionAndStoreUserId($id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['userId'] = $id;
    }

}