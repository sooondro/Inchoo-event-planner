<?php

namespace App\Models;

use PDO;

class User
{

    public static function signUpUser(PDO $db, array $values)
    {
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
        return $db->lastInsertId();
    }

    public static function signUpAdminUser(PDO $db, array $values)
    {
        $user = $db->prepare("
            INSERT INTO users (name, surname, email, password, admin)
            VALUES (:name, :surname, :email, :password, 1)
        ");

        $user->execute([
            'name' => $values['name'],
            'surname' => $values['surname'],
            'email' => $values['email'],
            'password' => $values['password'],
        ]);
    }

    public static function findUserByEmail(PDO $db, string $email)
    {
        $user = $db->prepare("
            SELECT * FROM users
            WHERE email = :email
        ");

        $user->bindParam(':email', $email);
        $user->execute();
        $user->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $user->fetch();
    }

    public static function findUserById(PDO $db, int $id)
    {
        $user = $db->prepare("
            SELECT * FROM users
            WHERE id = :id
        ");

        $user->bindParam(':id', $id);
        $user->execute();
        $user->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $user->fetch();
    }

}