<?php

namespace App\Controllers;

class LogoutController {

    /**
     * Serves as handle function for '/logout' uri
     * Destroys the session and redirects to homepage
     * @return void
     */
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
        die();
    }
}