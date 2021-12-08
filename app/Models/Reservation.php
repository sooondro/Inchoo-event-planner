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



}

