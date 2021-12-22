<?php

namespace App\Models;

use PDO;

class Event
{

    /**
     * Fetches all future events.
     * @param PDO $db
     * @return array|false
     */
    public static function fetchAllEvents(PDO $db)
    {
        $events = $db->prepare("
            SELECT * FROM events
            WHERE date > NOW()
        ");
        $events->execute();

        return $events->fetchAll(PDO::FETCH_CLASS, Event::class);

    }

    /**
     * Fetches and event object by id.
     * @param PDO $db
     * @param int $id
     * @return mixed
     */
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

    /**
     * Posts new event to the database.
     * @param PDO $db
     * @param array $values
     */
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

    /**
     * Updates admin event. Checks if a new image has been uploaded or not and calls adequate query.
     * @param PDO $db
     * @param array $values
     */
    public static function updateAdminEvent(PDO $db, array $values)
    {
        if ($values['image'] == '/public/Uploads/') {
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

    /**
     * Deletes and event by id from the database.
     * @param PDO $db
     * @param int $id
     */
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

    /**
     * Fetches and array of all admin event ids.
     * @param PDO $db
     * @param int $adminId
     * @return array
     */
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

    /**
     * Fetches all admin events that have passed from the database.
     * @param PDO $db
     * @param int $id
     * @return array|false
     */
    public static function fetchAllPastAdminEvents(PDO $db, int $id) {
        $query = $db->prepare("
            SELECT * FROM events
            WHERE admin_id = :id AND date < NOW()
        ");

        $query->execute([
            'id' => $id
        ]);

        return $query->fetchAll(PDO::FETCH_CLASS, Event::class);

    }

    /**
     * Fetches all future admin events from the database.
     * @param PDO $db
     * @param int $id
     * @return array|false
     */
    public static function fetchAllFutureAdminEvents(PDO $db, int $id) {
        $query = $db->prepare("
            SELECT * FROM events
            WHERE admin_id = :id AND date > NOW()
        ");

        $query->execute([
            'id' => $id
        ]);

        return $query->fetchAll(PDO::FETCH_CLASS, Event::class);

    }

    /**
     * Checks if an event is reservable by looking at max attendees number and count.
     * @param PDO $db
     * @param int $id
     * @return bool
     */
    public static function isEventReservable(PDO $db, int $id): bool
    {
        $event = self::fetchEventById($db, $id);
        if ($event->count >= $event->max_attendees) return false;
        return true;
    }

    /**
     * Increments event count value by 1.
     * @param PDO $db
     * @param int $id
     */
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

    /**
     * Decrements event count value by 1.
     * @param PDO $db
     * @param int $id
     */
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