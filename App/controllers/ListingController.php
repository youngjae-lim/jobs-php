<?php

namespace App\Controllers;

use Framework\Database;

class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();
        $data = [
            'listings' => $listings,
        ];
        loadView('listings/index', $data);
    }

    public function create()
    {
        loadView('listings/create');
    }

    public function show()
    {
        $id = $_GET['id'];
        inspect(compact('id'));

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', compact('id'))->fetch();
        $data = [
            'listing' => $listing,
        ];
        loadView('listings/show', $data);
    }
}
