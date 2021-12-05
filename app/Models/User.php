<?php

namespace App\Models;

use PDO;

class User
{

    public static function signUpUser(PDO $db, array $values)
    {
        $user = $db->prepare("
            INSERT INTO users (name, surname, email, password, admin)
            VALUES (:name, :surname, :email, :password, :admin)
        ");

        $user->execute([
            'name' => $values['name'],
            'surname' => $values['surname'],
            'email' => $values['email'],
            'password' => $values['password'],
            'admin' => $values['admin'],
        ]);
    }

}