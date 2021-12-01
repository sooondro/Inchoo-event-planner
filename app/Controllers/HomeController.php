<?php

namespace App\Controllers;

class HomeController
{

    public function index($response)
    {
        return $response->setBody('home');
    }

}