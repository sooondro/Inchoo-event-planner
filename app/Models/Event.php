<?php

namespace App\Models;

use DateTime;
use PDO;

class Event
{

    public static function fetchAllEvents(PDO $db)
    {
        $events = $db->prepare("
            SELECT * FROM events
            WHERE date > NOW()
        ");
        $events->execute();

        return $events->fetchAll(PDO::FETCH_CLASS, Event::class);

    }

    public static function fetchEventById(PDO $db, int $id)
    {
        $event = $db->prepare("
           SELECT * FROM events 
           WHERE id = :id
        ");

        $event->bindParam(':id', $id);
        $event->execute();
        $event->setFetchMode(PDO::FETCH_CLASS, Event::class);
        return $event->fetch();
    }

    public static function postNewEvent(PDO $db, array $values)
    {
        $event = $db->prepare("
            INSERT INTO events (name, date, location, max_attendees, description, admin_id, image)
            VALUES (:name, :date, :location, :max, :description, :adminId, :image)
        ");

        $event->execute([
            'name' => $values['name'],
            'date' => $values['date'],
            'location' => $values['location'],
            'max' => $values['max'],
            'description' => $values['description'],
            'adminId' => $values['adminId'],
            'image' => $values['image']
        ]);
    }

    public static function updateAdminEvent(PDO $db, array $values)
    {
        if ($values['image'] == '') {
            $query = $db->prepare("
            UPDATE events
            SET 
                name=:name,
                date=:date,
                location=:location,
                max_attendees=:max,
                description=:description
            WHERE id=:eventId AND admin_id=:adminId
        ");

            $query->execute([
                'name' => $values['name'],
                'date' => $values['date'],
                'location' => $values['location'],
                'max' => $values['max'],
                'description' => $values['description'],
                'eventId' => $values['eventId'],
                'adminId' => $values['adminId'],
            ]);
            return;
        }
        $query = $db->prepare("
            UPDATE events
            SET 
                name=:name,
                date=:date,
                location=:location,
                max_attendees=:max,
                description=:description,
                image = :image
            WHERE id=:eventId AND admin_id=:adminId
        ");

        $query->execute([
            'name' => $values['name'],
            'date' => $values['date'],
            'location' => $values['location'],
            'max' => $values['max'],
            'description' => $values['description'],
            'eventId' => $values['eventId'],
            'adminId' => $values['adminId'],
            'image' => $values['image']
        ]);
    }

    public static function deleteEventById(PDO $db, int $id)
    {
        $query = $db->prepare("
            DELETE FROM events
            WHERE id = :id
        ");

        $query->execute([
            'id' => $id
        ]);
    }

    public static function fetchAllAdminEventIdsAsArray(PDO $db, int $adminId): array
    {
        $query = $db->prepare("
            SELECT id FROM events
            WHERE admin_id = :id
        ");

        $query->execute([
            'id' => $adminId
        ]);

        $assocArray = $query->fetchAll(PDO::FETCH_ASSOC);
        $idArray = [];
        foreach ($assocArray as $event) {
            $idArray[] = $event['id'];
        }
        return $idArray;
    }

    public static function fetchAllAdminEvents(PDO $db, int $id) {
        $query = $db->prepare("
            SELECT * FROM events
            WHERE admin_id = :id
        ");

        $query->execute([
            'id' => $id
        ]);

        return $query->fetchAll(PDO::FETCH_CLASS, Event::class);

    }

    public static function isEventReservable(PDO $db, int $id): bool
    {
        $event = self::fetchEventById($db, $id);
        if ($event->count >= $event->max_attendees) return false;
        return true;
    }

    public static function incrementEventCount(PDO $db, int $id)
    {
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

    public static function decrementEventCount(PDO $db, int $id)
    {
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