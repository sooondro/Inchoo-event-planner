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

    public function index($response)
    {
        $events = Event::fetchAllEvents($this->db);
        return $response->setBody($response->renderView('index', [
            'events' => $events,
            'adminEvents' => $this->fetchAllAdminEventIds(),
            'reservedEvents' => $this->fetchAllUserReservationIdsAsArray(),
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }


    function fetchAllAdminEventIds(): array {
        if (!$this->authController->isAdmin()) {
            return [];
        }
        $adminId = $this->authController->getActiveUserId();
        return Event::fetchAllAdminEventIdsAsArray($this->db, $adminId);
    }

    function fetchAllUserReservationIdsAsArray(): array {
        return Reservation::fetchAllUserReservationIdsAsArray(
            $this->db,
            $this->authController->getActiveUserId()
        );
    }
}