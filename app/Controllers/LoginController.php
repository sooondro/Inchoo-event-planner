<?php

namespace App\Controllers;

use App\Models\User;
use PDO;

class LoginController
{
    protected $db;
    protected $errMessage = '';

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

    private function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('login'));
    }

    private function handlePostRequest($response)
    {

    }

    private function validateUserCredentials()
    {
        $user = $this->getUser();
        if (!$user) {
            $this->errMessage = 'There is no user with this email!';
            return false;
        }

    }

    private function getUser()
    {
        $email = $_POST['email'];
        $user = User::findUserByEmail($this->db, $email);
        if (count($user) > 0) {
            return $user;
        }
        return false;
    }

}