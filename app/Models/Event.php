<?php

namespace App\Models;

use PDO;

class Event
{

    public static function fetchAllEvents(PDO $db)
    {
        return $db->query("SELECT * FROM events")
            ->fetchAll(PDO::FETCH_CLASS, Event::class);
    }

    public static function fetchEventById(PDO $db, int $id)
    {

    }

    public static function postNewEvent(PDO $db, array $values)
    {
        $event = $db->prepare("
            INSERT INTO events (name, date, location, max_attendees, description, admin_id)
            VALUES (:name, :date, :location, :max, :description, :adminId)
        ");

        $event->execute([
            'name' => $values['name'],
            'date' => $values['date'],
            'location' => $values['location'],
            'max' => $values['max'],
            'description' => $values['description'],
            'adminId' => $values['adminId'],
        ]);
    }
}