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
            'pastEvents' => $this->fetchAllPastAdminEvents(),
            'futureEvents' => $this->fetchAllFutureAdminEvents(),
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName()
        ]));
    }

    private function fetchAllPastAdminEvents()
    {
        $adminId = $this->authController->getActiveUserId();
        return Event::fetchAllPastAdminEvents($this->db, $adminId);
    }
    private function fetchAllFutureAdminEvents()
    {
        $adminId = $this->authController->getActiveUserId();
        return Event::fetchAllFutureAdminEvents($this->db, $adminId);
    }


}