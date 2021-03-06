<?php

use App\App;
use App\Controllers\AdminEventsController;
use App\Controllers\AdminPanelController;
use App\Controllers\EditPasswordFormController;
use App\Controllers\EditUserFormController;
use App\Controllers\EventController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\ProfileController;
use App\Controllers\ReservationController;
use App\Controllers\SignupController;

error_reporting(E_ALL);
ini_set("display_errors", "On");
require_once '../vendor/autoload.php';
$app = new App;

$container = $app->getContainer();

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

$app->map('/create-admin', [new SignupController($container->db), 'createAdmin'], ['GET', 'POST']);

$app->map('/login', [new LoginController($container->db), 'index'], ['GET', 'POST']);

$app->get('/logout', [LogoutController::class, 'logout']);

$app->map('/create-event', [new EventController($container->db), 'index'], ['GET', 'POST']);

$app->post('/delete-event', [new EventController($container->db), 'delete']);

$app->map('/edit-event', [new EventController($container->db), 'edit'], ['GET', 'POST']);

$app->map('/reservations', [new ReservationController($container->db), 'index'], ['GET', 'POST']);

$app->post('/delete-reservation', [new ReservationController($container->db), 'delete']);

$app->map('/admin-events', [new AdminEventsController($container->db), 'index'], ['GET', 'POST']);

$app->map('/profile', [new ProfileController($container->db), 'index'], ['GET', 'POST']);

$app->map('/edit-user', [new EditUserFormController($container->db), 'index'], ['GET', 'POST']);

$app->map('/admin-panel', [new AdminPanelController($container->db), 'index'], ['GET', 'POST']);

$app->get('/delete-user', [new AdminPanelController($container->db), 'delete']);

$app->map('/edit-password', [new EditPasswordFormController($container->db), 'index'], ['GET', 'POST']);


$app->run();
