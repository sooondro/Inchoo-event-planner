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

    /**
     * Serves as a handler function for '/admin-events' uri.
     * Redirects to homepage if user does not have admin privilege.
     * @param $response
     */
    public function index($response)
    {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }
        return $this->handleGetRequest($response);
    }

    /**
     * GET request handler, returns admin-events view.
     * @param $response
     * @return mixed
     */
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

    /**
     * Fetches all admin events that have already passed.
     * @return array|false
     */
    private function fetchAllPastAdminEvents()
    {
        $adminId = $this->authController->getActiveUserId();
        return Event::fetchAllPastAdminEvents($this->db, $adminId);
    }

    /**
     * Fetches all future admin events.
     * @return array|false
     */
    private function fetchAllFutureAdminEvents()
    {
        $adminId = $this->authController->getActiveUserId();
        return Event::fetchAllFutureAdminEvents($this->db, $adminId);
    }


}