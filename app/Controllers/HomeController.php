<?php

namespace App\Controllers;

use App\Models\Event;
use PDO;

class HomeController
{

    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index($response)
    {
        $events = Event::fetchAllEvents($this->db);

        return $response->setBody($response->renderView('index', $events));
    }

    public function test($response){
        return $response->setBody('test');
    }

    public function fetchAllEvents() {
        return $this->db->query("SELECT * FROM events")
            ->fetchAll(PDO::FETCH_CLASS, Event::class);
    }


}