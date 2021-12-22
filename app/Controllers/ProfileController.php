<?php

namespace App\Controllers;

use App\Response;
use PDO;

class ProfileController extends AbstractController
{

    protected $db;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    /**
     * Serves as a handler function for '/profile' uri.
     * Checks if user is logged in.
     * Calls GET request handler function.
     * @param Response $response
     * @return Response|void
     */
    public function index(Response $response)
    {
        if (!$this->authController->isLoggedIn()) {
            header('Location: /');
            die();
        }

        return $this->handleGetRequest($response);
    }

    /**
     * GET request handler function. renders profile view.
     * @param Response $response
     * @return Response
     */
    private function handleGetRequest(Response $response): Response
    {
        return $response->setBody($response->renderView('profile', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName(),
            'user' => $this->authController->getCurrentUser()
        ]));
    }

}