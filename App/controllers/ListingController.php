<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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
    public function show($params)
    {
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing not found.');
            return;
        }
        $data = [
            'listing' => $listing,
        ];
        loadView('listings/show', $data);
    }

    /**
     * Store a new listing.
     *
     * @return void
     */
    public function store()
    {
        // inspect($_POST);

        $allowedFields = [
            'title',
            'description',
            'salary',
            'requirements',
            'benefits',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
        ];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        // NOTE: Hardcode user_id for now
        $newListingData['user_id'] = 1;

        // Sanitize data
        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = [
            'title',
            'description',
            'email',
            'city',
            'state',
        ];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required.';
            }
        }

        if (!empty($errors)) {
            $data = [
                'errors' => $errors,
                'listing' => $newListingData,
            ];
            loadView('listings/create', $data);
            return;
        } else {
            $success = true;

            $data = [
                'success' => $success,
            ];
            loadView('listings/create', $data);
        }
    }
}
