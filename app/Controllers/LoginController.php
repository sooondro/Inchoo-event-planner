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

    public function index($response)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prepareUserInput();
            return $this->handlePostRequest($response);
        }
        return $this->handleGetRequest($response);
    }

    private function handleGetRequest($response)
    {
        if ($this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('login', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    private function handlePostRequest($response)
    {
        if ($this->validateUserCredentials()) {
            $this->startSessionAndStoreUserId($this->getUser()->id);
            header('Location: /');
            die();

            /*return $response->setBody($response->renderView('login', [
                'confirmation' => 'success',
                'message' => 'You have successfully logged in'
            ]));*/
        }
        return $response->setBody($response->renderView('login', [
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
    }

    private function validateUserCredentials(): bool
    {
        $user = $this->getUser();
        if (!$user) {
            $this->errMessage = 'Invalid email or password';
            return false;
        }

        if (!$this->verifyPassword($this->formValues['password'], $user->password)) {
            $this->errMessage = 'Invalid email or password';
            return false;
        }
        return true;
    }

    private function getUser()
    {
        $email = $_POST['email'];
        $user = User::findUserByEmail($this->db, $email);
        if ($user) {
            return $user;
        }
        return false;
    }

    private function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    private function fetchFormValuesAsArray(): array
    {
        $values = [];
        $values['email'] = $_POST['email'];
        $values['password'] = $_POST['password'];
        return $values;
    }

    private function startSessionAndStoreUserId($id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['userId'] = $id;
    }

}