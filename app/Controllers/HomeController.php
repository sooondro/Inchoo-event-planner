<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use PDO;

class HomeController extends AbstractController
{

    protected $db;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    /**
     * Serves as a handler function for '/' uri, returns hoepage view
     * @param $response
     * @return mixed*
     */
    public function index($response)
    {
        $events = Event::fetchAllEvents($this->db);
        return $response->setBody($response->renderView('index', [
            'events' => $events,
            'adminEvents' => $this->fetchAllAdminEventIds(),
            'reservedEvents' => $this->fetchAllUserReservationIdsAsArray(),
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName()
        ]));
    }

    /**
     * Fetches all events created by currently logged in admin.
     * If admin has no events or logged in user is not admin, returns an empty array
     * @return array
     */
    function fetchAllAdminEventIds(): array {
        if (!$this->authController->isAdmin()) {
            return [];
        }
        $adminId = $this->authController->getActiveUserId();
        return Event::fetchAllAdminEventIdsAsArray($this->db, $adminId);
    }


    /**
     * Fetches an array of event ids reserved by currently logged in user
     * @return array
     */
    function fetchAllUserReservationIdsAsArray(): array {
        return Reservation::fetchAllUserReservationIdsAsArray(
            $this->db,
            $this->authController->getActiveUserId()
        );
    }
}