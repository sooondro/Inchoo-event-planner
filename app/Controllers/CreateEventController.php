<?php

namespace App\Controllers;

class CreateEventController
{

    public function index($response)
    {
        return $response->setBody($response->renderView('index', ['key' => 'value']));
    }

    public function postEvent($response) {

    }

}