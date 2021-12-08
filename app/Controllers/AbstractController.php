<?php

namespace App\Controllers;


use PDO;

class AbstractController
{
    protected $authController;

    public function __construct(PDO $db)
    {
        $this->authController = new AuthController($db);
    }

}

