<?php

namespace App\Controllers;

use App\Models\User;
use App\Response;
use PDO;

class AdminPanelController extends AbstractController
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        }

        return $this->handleGetRequest($response);
    }

    private function handleGetRequest(Response $response): Response
    {
        return $response->setBody($response->renderView('admin-panel', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName(),
            'users' => User::fetchAllUsers($this->db)
        ]));
    }

    private function handlePostRequest()
    {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }

        $userId = $_POST['id'];

        User::makeUserAdmin($this->db, $userId);
        header('Location: /admin-panel');
        die();
    }

}