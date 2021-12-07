<?php

function isAuth(): bool
{
    if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
        return true;
    }
    return false;
}