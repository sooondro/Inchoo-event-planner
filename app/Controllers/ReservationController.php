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

    /**
     * Serves as handle function for 'reservations' uri
     * If no user is logged in, redirects to homepage
     * Checks if the request method is POST or GET and calls adequate function
     * @param $response
     * @return void
     */
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

    /**
     * Serves as handle function for '/delete-reservation' uri
     * checks if user is logged in, if not, redirects to homepage
     * After deleting reservation, redirects back to the page from which the request was sent
     * @return void
     */
    public function delete() {
        if (!$this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }
        $eventId = $_POST['eventId'];
        $location = $_POST['location'];
        $userId = $this->authController->getActiveUserId();

        Reservation::deleteUserReservation($this->db, $userId, $eventId);

        header('Location: ' . $location);
        die();
    }

    /**
     * GET request handle function
     * Renders reservation page
     * @param $response
     * @return mixed
     */
    private function handleGetRequest($response)
    {
        return $response->setBody($response->renderView('reservations', [
            'events' => $this->fetchAllUserReservedEvents(),
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    /**
     * Fetches all current user reservations as array
     * @return array|false
     */
    private function fetchAllUserReservations()
    {
        $userId = $this->authController->getActiveUserId();
        return Reservation::fetchAllUserReservations($this->db, $userId);
    }

    /**
     * Fetches all events reserved by logged in user
     * @return array
     */
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

    /**
     * POST request handle function
     * If no user is logged in, redirects to homepage
     * Checks if event is reservable, if it is, creates a new reservation
     * @return void
     */
    private function handlePostRequest()
    {
        $eventId = $_POST['eventId'];
        $location = $_POST['location'];
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
        }

        header('Location: /');
        die();
    }
}