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

    public function delete($response) {
        if (!$this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }
        $eventId = $_POST['eventId'];
        $userId = $this->authController->getActiveUserId();

        Reservation::deleteUserReservation($this->db, $userId, $eventId);

        header('Location: /reservations');
        die();
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
        if (count($reservations) > 0) {
            foreach ($reservations as $reservation) {
                $event = Event::fetchEventById($this->db, $reservation->event_id);
                $events[] = $event;
            }
        }
        return $events;
    }

    private function handlePostRequest($response)
    {
        $eventId = $_POST['eventId'];
        if (!$this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }

        if (Event::isEventReservable($this->db, $eventId)) {
            Reservation::postReservation(
                $this->db,
                $this->authController->getActiveUserId(),
                $eventId
            );
            header('Location: /reservations');
            die();
        }

        header('Location: /');
        die();
    }
}