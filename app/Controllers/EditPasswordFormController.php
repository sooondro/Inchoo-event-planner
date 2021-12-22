<?php

namespace App\Controllers;

use App\Models\User;
use App\Response;
use Exception;
use PDO;

class EditPasswordFormController extends AbstractController
{
    protected $db;
    protected $errMessage = '';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    /**
     * Serves as a handler function for '/edit-password' uri.
     * Redirects to homepage if user is not logged in.
     * Checks request method and calls adequate handler function.
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
            return $this->handlePostRequest($response);
        }

        return $this->handleGetRequest($response);
    }

    /**
     * GET request handler function. Renders edit-password view.
     * @param Response $response
     * @return Response
     */
    private function handleGetRequest(Response $response): Response
    {
        return $response->setBody($response->renderView('edit-password', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName(),
        ]));
    }

    /**
     * POST request handler function.
     * If user is not logged in, redirects to homepage.
     * If validation fails, renders edit-password page with error message displayed.
     * Otherwise, changes user password and redirects to homepage.
     * @param Response $response
     * @return Response|void
     */
    private function handlePostRequest(Response $response)
    {
        if (!$this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }
        if ($this->validateUserInput()) {
            User::changeUserPassword($this->db, $this->authController->getActiveUserId(), $this->hashPassword());
            header('Location: /');
            die();
        }
        return $response->setBody($response->renderView('edit-password', [
            'confirmation' => 'fail',
            'message' => $this->errMessage,
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName()
        ]));
    }

    /**
     * Hashes user password for storing in database.
     * Returns the hashes password.
     * @return string
     */
    private function hashPassword(): string
    {
        $password = $_POST['password'];
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    /**
     * Validates user input.
     * If validation passes, returns true.
     * @return bool
     */
    private function validateUserInput(): bool
    {
        try {
            if (
                $this->passwordsMatch() &&
                $this->verifyPassword($_POST['current-password'], $this->authController->getCurrentUserPassword())
            ) return true;
        } catch (Exception $e) {
           $this->errMessage = $e->getMessage();
        }
        return false;
    }

    /**
     * Checks if the password and the repeated password match.
     * @return bool
     * @throws Exception
     */
    private function passwordsMatch(): bool
    {
        if ($_POST['password'] == $_POST['repeated-password']) return true;
        throw new Exception('New passwords have to match');
    }

    /**
     * Checks if the logged in user hashed password matches the current password passed in the form.
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     * @throws Exception
     */
    private function verifyPassword(string $password, string $hashedPassword): bool
    {
        if (password_verify($password, $hashedPassword)) return true;
        throw new Exception('Invalid current password');
    }

}