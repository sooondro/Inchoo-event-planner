<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use PDO;

class ReservationController extends AbstractController
{

    protected $db;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    public function index($response)
    {
        if (!$this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handlePostRequest($response);
        }
        return $this->handleGetRequest($response);
    }

    private function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('reservations', [
            'events' => $this->fetchAllUserReservedEvents(),
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    private function fetchAllUserReservations()
    {
        $userId = $this->authController->getActiveUserId();
        return Reservation::fetchAllUserReservations($this->db, $userId);
    }

    private function fetchAllUserReservedEvents(): array
    {
        $events = [];
        $reservations = $this->fetchAllUserReservations();
        if(count($reservations) > 0) {
            foreach ($reservations as $reservation) {
                $event = Event::fetchEventById($this->db, $reservation->eventId);
                var_dump($event);
                $events[] = $event;
            }
        }
        return $events;
    }
}