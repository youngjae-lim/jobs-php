<?php

namespace App\Controllers;

use Framework\Database;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    public function create()
    {
        loadView('users/create');
    }

    public function login()
    {
        loadView('users/login');
    }
}
