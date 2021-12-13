<?php

namespace App\Controllers;

class LogoutController {

    /**
     * Serves as handle function for '/logout' uri
     * Destroys the session and redirects to homepage
     * @param $response
     * @return void
     */
    public function logout($response) {
        session_start();
        session_destroy();
        header('Location: /');
        die();
    }
}