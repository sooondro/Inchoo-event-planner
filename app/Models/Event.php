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
        $event = $db->prepare("
           SELECT * FROM events 
           WHERE id = :id
        ");

        $event->bindParam(':id', $id);
        $event->execute();

        $event = $event->fetchAll(PDO::FETCH_CLASS, Event::class);

        return $event[0];
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

    public static function isEventReservable(PDO $db, $id): bool
    {
        $event = self::fetchEventById($db, $id);
        if ($event->count >= $event->max_attendees) return false;
        return true;
    }

    public static function incrementEventCount(PDO $db, $id) {
        $event = self::fetchEventById($db, $id);
        $newCount = $event->count + 1;

        $query = $db->prepare('
            UPDATE events
            SET count = :count 
            WHERE id = :id
        ');


        $query->execute([
            'id' => $id,
            'count' => $newCount
        ]);

    }

    public static function decrementEventCount(PDO $db, $id) {
        $event = self::fetchEventById($db, $id);
        $newCount = $event->count - 1;

        $query = $db->prepare('
            UPDATE events
            SET count = :count 
            WHERE id = :id
        ');


        $query->execute([
            'id' => $id,
            'count' => $newCount
        ]);

    }
}