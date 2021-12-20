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


    private function handleGetRequest(Response $response): Response
    {
        return $response->setBody($response->renderView('edit-user', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName(),
            'user' => $this->authController->getCurrentUser()
        ]));
    }

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

    private function prepareUserInput()
    {
        $this->formValues = $this->fetchFormValuesAsArray();
        $this->trimAllWhitespaceFromUserInput();
    }

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

    private function trimAllWhitespaceFromUserInput()
    {
        $this->formValues['name'] = trim($this->formValues['name']);
        $this->formValues['surname'] = trim($this->formValues['surname']);
        $this->formValues['email'] = trim($this->formValues['email']);
    }

    private function validateUserInput(): bool
    {
        $validator = new EditUserValidator();
        try {
            if (
                $this->authController->getActiveUserEmail() !== $this->formValues['email'] &&
                $this->userExists()
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

    private function verifyPassword(string $password, string $hashedPassword): bool
    {
        if (password_verify($password, $hashedPassword)) return true;
        throw new Exception('Invalid password');
    }

}