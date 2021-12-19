<?php

namespace App\Controllers;

use App\Response;
use PDO;

class EditUserFormController extends AbstractController
{

    protected $db;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    public function index(Response $response)
    {
        if (!$this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }

        return $this->handleGetRequest($response);
    }


    private function handleGetRequest(Response $response): Response
    {
        return $response->setBody($response->renderView('edit-user', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName(),
            'user' => $this->authController->getCurrentUser()
        ]));
    }

}