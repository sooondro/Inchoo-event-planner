<?php

namespace App\Controllers;

use App\Models\User;
use PDO;

class SignupController
{

    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index($response)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handlePostRequest($response);
        }
        return $this->handleGetRequest($response);
    }

    public function handlePostRequest($response)
    {
        if ($this->validateUserInput()) {
            $values = $this->fetchFormValuesAsArray();
            User::signUpUser($this->db, $values);
            return $response->setBody($response->renderView('signup', [
                'confirmation' => 'success',
                'message' => 'You have successfully signed up'
            ]));
        }
        return $response->setBody($response->renderView('signup', [
            'confirmation' => 'fail',
            'message' => 'Invalid input'
        ]));
    }

    public function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('signup'));
    }

    public function validateUserInput(): bool
    {
        if ($this->validatePassword()) return true;
        return false;
    }

    public function validatePassword(): bool
    {
        if ($_POST['password'] === $_POST['repeated-password']) return true;
        return false;
    }

    public function hashPassword() {
        $password = $_POST['password'];
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    public function validateAdminCheckbox(): bool
    {
        if ($_POST['admin'] === null) return false;
        return true;
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

}