<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use AuthController;
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
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn()
        ]));
    }

    public function postReservation($response) {
        $userId = $_POST['userId'];
        $eventId = $_POST['eventId'];




    }

    public function test($response){
        return $response->setBody('test');
    }

    public function fetchAllEvents() {
        return $this->db->query("SELECT * FROM events")
            ->fetchAll(PDO::FETCH_CLASS, Event::class);
    }


}