<?php

namespace App\Controllers;

use App\Models\User;
use PDO;

class AuthController
{
    private $currentUser;
    private $db;

    public function __construct(PDO $db)
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
        $this->db = $db;
        $userId = $_SESSION['userId'] ?? null;
        if ($userId) {
            $user = User::findUserById($this->db,$userId);
            $this->currentUser = $user;
        }
    }

    public function isLoggedIn(): bool
    {

        if ($this->currentUser) {
            return true;
        }
        return false;
    }

    public function isAdmin(): bool
    {
        if ($this->currentUser) {
            if ($this->currentUser->admin) return true;
        }
        return false;
    }
}