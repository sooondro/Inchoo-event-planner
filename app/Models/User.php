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

    public static function editUser(PDO $db, array $values) {
        $query = $db->prepare("
            UPDATE users
            SET name = :name, surname = :surname, email = :email
            WHERE id = :id
        ");

        $query->execute([
           'name' => $values['name'],
           'surname' => $values['surname'],
           'email' => $values['email'],
           'id' => $values['id'],
        ]);
    }

    public static function fetchAllUsers(PDO $db) {
        $query = $db->query("
            SELECT * FROM users
        ");

        return $query->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    public static function makeUserAdmin(PDO $db, int $userId) {
        $query = $db->prepare("
            UPDATE users
            SET admin = 1
            WHERE id = :userId
        ");

        $query->execute([
            'userId' => $userId
        ]);
    }

    public static function changeUserPassword(PDO $db, int $id, string $password) {
        $query = $db->prepare("
            UPDATE users
            SET password = :password
            WHERE id = :userId
        ");

        $query->execute([
            'userId' => $id,
            'password' => $password
        ]);
    }
}