<?php

use App\App;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\SignupController;

require_once 'vendor/autoload.php';




$app = new App;

$container = $app->getContainer();

$container['errorHandler'] = function () {
    return function ($response) {

        return $response->setBody('page not found')->withStatus(404);
    };
};

$container['config'] = function () {
    return [
        'db_driver' => 'mysql',
        'db_host' => 'event-planner.com',
        'db_name' => 'event-planner',
        'db_user' => 'root',
        'db_password' => 'root'
    ];
};

$container['db'] = function ($c) {
    return new PDO(
        $c->config['db_driver'] . ':host=' . $c->config['db_host'] . ';dbname=' . $c->config['db_name'],
        $c->config['db_user'],
        $c->config['db_password']
    );
};


$app->get('/', [new HomeController($container->db), 'index']);

$app->map('/signup', [new SignupController($container->db), 'index'], ['GET', 'POST']);

$app->map('/login', [new LoginController($container->db), 'index'], ['GET', 'POST']);

$app->get('/test', [new HomeController($container->db), 'test']);

$app->get('/create-event', [HomeController::class, 'index']);



$app->run();
