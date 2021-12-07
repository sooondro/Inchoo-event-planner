<?php

use App\Models\User;

function isAdmin(PDO $db, $id):bool {
    $user = User::findUserById($db, $id);
    return $user->admin;
}