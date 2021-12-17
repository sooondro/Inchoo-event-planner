<?php

namespace App\Controllers;

use App\Models\Event;
use PDO;

class AdminEventsController extends AbstractController
{

    protected $db;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    public function index($response)
    {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }
        return $this->handleGetRequest($response);
    }

    private function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('admin-events', [
            'events' => $this->fetchAllAdminEvents(),
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName()
        ]));
    }

    private function fetchAllAdminEvents()
    {
        $adminId = $this->authController->getActiveUserId();
        return Event::fetchAllAdminEvents($this->db, $adminId);
    }


}