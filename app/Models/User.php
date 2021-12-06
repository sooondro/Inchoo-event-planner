<?php

namespace App\Models;

use PDO;

class User
{

    public static function signUpUser(PDO $db, array $values)
    {
        /*
        $user = $db->prepare("
            INSERT INTO users (name, surname, email, password, admin)
            VALUES (:name, :surname, :email, :password, :admin)
        ");
        */

        $user = $db->prepare("
            INSERT INTO users (name, surname, email, password)
            VALUES (:name, :surname, :email, :password)
        ");

        $user->execute([
            'name' => $values['name'],
            'surname' => $values['surname'],
            'email' => $values['email'],
            'password' => $values['password'],
        ]);
            //'admin' => $values['admin'],
    }

    public static function findUserByEmail(PDO $db, string $email) {
        $user = $db->prepare("
            SELECT * FROM users
            WHERE email = :email
        ");

        $user->bindParam(':email', $email);
        $user->execute();
        return $user->fetchAll(PDO::FETCH_CLASS, User::class);

    }

}