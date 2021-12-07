<?php

namespace App\Controllers;

use App\Models\User;
use App\Validators\EmailValidator;
use App\Validators\NameValidator;
use App\Validators\PasswordValidator;
use App\Validators\SurnameValidator;
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
            return $this->handlePostRequest($response);
        }
        return $this->handleGetRequest($response);
    }

    private function handlePostRequest($response)
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

    private function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('signup'));
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
        //$values['admin'] = $this->validateAdminCheckbox();
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

    /*    public function validateName(): bool
        {
            $name = $this->formValues['name'];
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
            $name = $this->formValues['name'];


            return true;
        }*/

    /*    public function validateEmail(): bool
        {
            $email = $this->formValues['email'];
            return true;

        }*/
    /*    public function validateAdminCheckbox(): bool
        {
            if (isset($_POST['admin']) && $_POST['admin'] == 1) return 1;
            return 0;
        }
    */

}