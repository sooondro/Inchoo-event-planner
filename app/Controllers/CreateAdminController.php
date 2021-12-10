<?php

namespace App\Controllers;

use PDO;

class CreateAdminController extends AbstractController
{

    protected $db;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }



}