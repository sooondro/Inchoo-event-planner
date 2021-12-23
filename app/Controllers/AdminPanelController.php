<?php

namespace App\Controllers;

use App\Models\User;
use App\Response;
use PDO;

class AdminPanelController extends AbstractController
{
    protected $db;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    /**
     * Serves as a handler function for '/admin-panel' uri.
     * Checks if user is admin.
     * Checks request method and calls adequate handler function.
     * @param Response $response
     * @return Response|void
     */
    public function index(Response $response)
    {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequestMakeAdmin();
        }

        return $this->handleGetRequest($response);
    }

    /**
     * GET request handler function. Renders admin-panel view.
     * @param Response $response
     * @return Response
     */
    private function handleGetRequest(Response $response): Response
    {
        return $response->setBody($response->renderView('admin-panel', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName(),
            'users' => User::fetchAllUsers($this->db)
        ]));
    }

    /**
     * POST request handler function.
     * Calls function used for turning user to admin and redirects to homepage.
     */
    private function handlePostRequestMakeAdmin()
    {
        $userId = $_POST['id'];

        User::makeUserAdmin($this->db, $userId);
        header('Location: /admin-panel');
        die();
    }

    /**
     * Serves as a handler function for '/delete-user' uri.
     * Checks if user is admin, redirects if not.
     * Checks if there is userId in query params in url and if it is numeric.
     * Calls function for deleting user and rerenders page with new users array.
     * @param Response $response
     * @return Response|void
     */
    public function delete(Response $response) {
        if (!$this->authController->isAdmin()) {
            header('Location: /');
            die();
        }

        if (empty($_GET['userId']) || !is_numeric($_GET['userId'])) {
            header('Location: /');
            die();
        }

        $userId = $_GET['userId'];

        if ($this->isUserAdmin($userId)) {
            header('Location: /admin-panel');
            die();
        }

        User::deleteUserById($this->db, $userId);
        return $response->setBody($response->renderView('admin-panel', [
            'isAdmin' => $this->authController->isAdmin(),
            'isLoggedIn' => $this->authController->isLoggedIn(),
            'userName' => $this->authController->getActiveUserName(),
            'users' => User::fetchAllUsers($this->db)
        ]));
    }

    private function isUserAdmin(int $userId) {
        return User::checkUserAdminById($this->db, $userId);
    }

}