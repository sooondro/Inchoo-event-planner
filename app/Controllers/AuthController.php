<?php

namespace App\Controllers;

use App\Models\User;
use PDO;


/**
 * Used in every controller to check user auth state
 */
class AuthController
{
    private $currentUser;
    private $db;

    /**
     * Checks if there is a logged in user
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->db = $db;
        $userId = $_SESSION['userId'] ?? null;
        if ($userId) {
            $user = User::findUserById($this->db, $userId);
            $this->currentUser = $user;
        }
    }

    /**
     * Checks if current user is logged in
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return (bool) $this->currentUser;
    }

    /**
     * Checks if current user is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->currentUser && $this->currentUser->admin;
    }

    /**
     * Returns current user id or null if no user is logged in
     * @return null
     */
    public function getActiveUserId()
    {
        return $this->currentUser->id ?? null;
    }

    public function getActiveUserName()
    {
        if ($this->currentUser) return $this->currentUser->name . ' ' . $this->currentUser->surname;
        return null;
    }

    public function getCurrentUser() {
        return $this->currentUser;
    }
}