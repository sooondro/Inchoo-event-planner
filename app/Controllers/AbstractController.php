<?php

namespace App\Controllers;


use PDO;

/**
 * Class that every controller extends so it has access to AuthController
 */
class AbstractController
{
    protected $authController;

    public function __construct(PDO $db)
    {
        $this->authController = new AuthController($db);
    }

}

