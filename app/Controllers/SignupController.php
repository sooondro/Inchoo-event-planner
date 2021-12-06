<?php

namespace App\Controllers;

use App\Models\User;
use PDO;

class SignupController
{

    protected $db;
    protected $formValues = [];
    protected $errMessage = '';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index($response)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prepareUserInput();
            var_dump($this->formValues);
            die();
            return $this->handlePostRequest($response);
        }
        return $this->handleGetRequest($response);
    }

    public function handlePostRequest($response)
    {
        if ($this->validateUserInput()) {
            User::signUpUser($this->db, $this->formValues);
            return $response->setBody($response->renderView('signup', [
                'confirmation' => 'success',
                'message' => 'You have successfully signed up'
            ]));
        }
        return $response->setBody($response->renderView('signup', [
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'formValues' => $this->formValues
        ]));
    }

    public function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('signup'));
    }

    public function prepareUserInput()
    {
        $this->formValues = $this->fetchFormValuesAsArray();
        $this->trimAllWhitespaceFromUserInput();
        $this->uppercaseFirstLetterOfNameAndSurname();
    }

    public function uppercaseFirstLetterOfNameAndSurname()
    {
        $this->formValues['name'] = ucfirst($this->formValues['name']);
        $this->formValues['surname'] = ucfirst($this->formValues['surname']);
    }

    public function trimAllWhitespaceFromUserInput()
    {
        $this->formValues['name'] = trim($this->formValues['name']);
        $this->formValues['surname'] = trim($this->formValues['surname']);
        $this->formValues['email'] = trim($this->formValues['email']);
    }

    public function fetchFormValuesAsArray(): array
    {
        $values = [];
        $values['name'] = $_POST['name'];
        $values['surname'] = $_POST['surname'];
        $values['email'] = $_POST['email'];
        $values['password'] = $this->hashPassword();
        $values['admin'] = $this->validateAdminCheckbox();
        return $values;
    }

    public function validateUserInput(): bool
    {
        if (
            $this->validatePassword()
            && $this->validateName()
            && $this->validateSurname()
            && $this->validateEmail()
        ) return true;
        return false;
    }

    public function hashPassword(): string
    {
        $password = $_POST['password'];
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    public function validatePassword(): bool
    {
        if (strlen($_POST['password']) < 6) {
            $this->errMessage = 'Password has to contain 6 or more letters';
            return false;
        }
        if ($_POST['password'] !== $_POST['repeated-password']){
            $this->errMessage = 'Passwords have to match';
            return false;
        }
        return true;
    }

    public function validateName(): bool
    {
        $name = $_POST['name'];
        if (empty($name)) {
            $this->errMessage = 'Name cannot be empty';
            return false;
        }
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $this->errMessage = "Surname has to contain letters or ' or -";
            return false;
        }
        return true;
    }

    public function validateSurname(): bool
    {
        $name = $_POST['name'];
        if (empty($name)) {
            $this->errMessage = 'Surname cannot be empty';
            return false;
        }
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $this->errMessage = "Surname has to contain letters or ' or -";
            return false;
        }
        return true;
    }

    public function validateEmail(): bool
    {
        $email = $_POST['email'];
        if (empty($email)) {
            $this->errMessage = "Email cannot be empty";
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errMessage = 'Email is not valid format';
            return false;
        }
        return true;
    }

    public function validateAdminCheckbox(): bool
    {
        if (isset($_POST['admin']) && $_POST['admin'] == 1) return 1;
        return 0;
    }




}