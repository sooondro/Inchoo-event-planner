<?php

namespace App\Models;

use PDO;

class Event {

    public static function fetchAllEvents(PDO $db) {
        return $db->query("SELECT * FROM events")
            ->fetchAll(PDO::FETCH_CLASS, Event::class);
    }

    public static function fetchEventById(PDO $db, int $id) {

    }
}