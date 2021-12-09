<?php

namespace App\Models;

use PDO;

class Reservation
{

    public static function postReservation(PDO $db, $userId, $eventId)
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

    public static function fetchAllUserReservations(PDO $db, $userId)
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

    public static function deleteUserReservation(PDO $db, $userId, $eventId)
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

