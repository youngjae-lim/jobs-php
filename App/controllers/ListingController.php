<?php

namespace App\Controllers;

use Framework\Database;

class ListingController
{
    protected $db;

    /**
     * Create a new ListingController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the listings page.
     *
     * @return void
     */
    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();
        $data = [
            'listings' => $listings,
        ];
        loadView('listings/index', $data);
    }

    /**
     * Show the create listing page.
     *
     * @return void
     */
    public function create()
    {
        loadView('listings/create');
    }

    /**
     * Show the listing page.
     *
     * @return void
     */
    public function show()
    {
        $id = $_GET['id'];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', compact('id'))->fetch();
        $data = [
            'listing' => $listing,
        ];
        loadView('listings/show', $data);
    }
}
