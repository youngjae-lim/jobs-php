<?php

namespace App\Controllers;

use Framework\Database;

class HomeController
{
    protected $db;

    /**
     * Create a new HomeController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the home page.
     *
     * @return void
     */
    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings LIMIT 6')->fetchAll();

        $data = [
            'listings' => $listings,
        ];

        loadView('home', $data);
    }
}
