<?php

namespace App\Controllers;

class LogoutController {
    public function logout($response) {
        session_start();
        session_destroy();
        header('Location: /');
        die();
    }
}