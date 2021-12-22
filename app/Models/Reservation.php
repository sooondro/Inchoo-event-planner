<?php

namespace App\Models;

use PDO;

class Reservation
{

    /**
     * Posts a new reservation to the database.
     * @param PDO $db
     * @param int $userId
     * @param int $eventId
     */
    public static function postReservation(PDO $db, int $userId, int $eventId)
    {
        $query = $db->prepare("
           INSERT INTO reservations (user_id, event_id)
           VALUES (:userId, :eventId)
        ");

        $query->execute([
            'userId' => $userId,
            "eventId" => $eventId
        ]);

        Event::incrementEventCount($db, $eventId);
    }

    /**
     * Fetches all user reservations.
     * @param PDO $db
     * @param int $userId
     * @return array|false
     */
    public static function fetchAllUserReservations(PDO $db, int $userId)
    {
        $query = $db->prepare("
            SELECT * FROM reservations
            WHERE user_id = :userId
        ");

        $query->execute([
            "userId" => $userId
        ]);

        return $query->fetchAll(PDO::FETCH_CLASS, Reservation::class);
    }

    /**
     * Deletes a user event reservation from the database.
     * @param PDO $db
     * @param int $userId
     * @param int $eventId
     */
    public static function deleteUserReservation(PDO $db, int $userId, int $eventId)
    {

        $query = $db->prepare("
            DELETE FROM reservations
            WHERE user_id = :userId AND event_id = :eventId
        ");

        $query->execute([
            'userId' => $userId,
            'eventId' => $eventId
        ]);

        Event::decrementEventCount($db, $eventId);

    }

    /**
     * Fetches and array of user reservation ids.
     * @param PDO $db
     * @param $userId
     * @return array
     */
    public static function fetchAllUserReservationIdsAsArray(PDO $db, $userId): array
    {
        $query = $db->prepare("
            SELECT event_id FROM reservations
            WHERE user_id = :userId
        ");

        $query->execute([
            "userId" => $userId
        ]);

        $assocArray = $query->fetchAll(PDO::FETCH_ASSOC);
        $idArray = [];
        foreach ($assocArray as $item) {
            $idArray[] = $item['event_id'];
        }
        return $idArray;
    }


}

