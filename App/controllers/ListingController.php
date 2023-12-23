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
        if (! $listing) {
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
            'tags',
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
            'salary',
            'email',
            'city',
            'state',
        ];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || ! Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field).' is required.';
            }
        }

        if (! empty($errors)) {
            $data = [
                'errors' => $errors,
                'listing' => $newListingData,
            ];
            loadView('listings/create', $data);

            return;
        } else {
            $fields = [];
            $values = [];

            foreach ($newListingData as $key => $value) {
                // Convert empty strings to null
                if (empty($value)) {
                    $newListingData[$key] = null;
                }

                // Extract keys and values for query
                $fields[] = $key;
                $values[] = ':'.$key;
            }

            // Convert arrays to comma separated strings
            $fields = implode(', ', $fields);
            $values = implode(', ', $values);

            $query = "INSERT INTO listings ($fields) VALUES ($values)";

            $this->db->query($query, $newListingData);

            redirect('listings');
        }
    }

    /**
     * Delete a listing.
     *
     * @return void
     */
    public function destroy($params)
    {
        // Find a listing by id
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // If listing doesn't exist, show 404 page
        if (! $listing) {
            ErrorController::notFound('Listing not found.');

            return;
        }

        // Delete listing
        $this->db->query('DELETE FROM listings WHERE id = :id', $params)->fetch();

        // Set flash message
        $_SESSION['success_message'] = 'Listing deleted successfully.';

        // Redirect to listings page
        redirect('listings');
    }

    /**
     * Edit a listing.
     *
     * @return void
     */
    public function edit($params)
    {
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if (! $listing) {
            ErrorController::notFound('Listing not found.');

            return;
        }
        $data = [
            'listing' => $listing,
        ];

        loadView('listings/edit', $data);
    }

    public function update($params)
    {
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if (! $listing) {
            ErrorController::notFound('Listing not found.');

            return;
        }

        $allowedFields = [
            'title',
            'description',
            'salary',
            'requirements',
            'benefits',
            'tags',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
        ];

        $updateListingData = array_intersect_key($_POST, array_flip($allowedFields));

        // Sanitize data
        $updateListingData = array_map('sanitize', $updateListingData);

        $requiredFields = [
            'title',
            'description',
            'salary',
            'email',
            'city',
            'state',
        ];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateListingData[$field]) || ! Validation::string($updateListingData[$field])) {
                $errors[$field] = ucfirst($field).' is required.';
            }
        }

        if (! empty($errors)) {
            $updateListingData['id'] = $listing->id;

            // Change $updateLisingData from associative array to object
            // so we can access it in the view using $listing->field
            $updateListingData = (object) $updateListingData;

            $data = [
                'errors' => $errors,
                'listing' => $updateListingData,
            ];
            loadView('listings/edit', $data);

            return;
        } else {
            foreach ($updateListingData as $key => $value) {
                // Convert empty strings to null
                if (empty($value)) {
                    $updateListingData[$key] = null;
                }
            }

            $updateFields = [];

            foreach (array_keys($updateListingData) as $key) {
                $updateFields[] = $key.' = :'.$key;
            }

            $updateFields = implode(', ', $updateFields);

            // Add id to updateListingData
            $updateListingData['id'] = $listing->id;

            // inspectAndDie($updateFields);

            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

            $this->db->query($updateQuery, $updateListingData);

            $_SESSION['success_message'] = 'Listing updated successfully.';

            redirect('listings/'.$listing->id);
        }
    }
}
