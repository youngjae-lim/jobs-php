<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the form to create a new user.
     *
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    /**
     * Show the form to login a user.
     *
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * Store a new user.
     *
     * @return void
     */
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        $errors = [];

        // Validation
        if (! Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (! Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 chacracters.';
        }

        if (! Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must at least 6 chacracters.';
        }

        if (! Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match.';
        }

        // If validation fails, show errors
        if (! empty($errors)) {
            $data = [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ],
            ];
            loadView('users/create', $data);

            return;
        }

        $params = [
            'email' => $email,
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user) {
            $errors['email'] = 'Email address already in use.';
            $data = [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ],
            ];
            loadView('users/create', $data);

            return;
        }

        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $params);

        // Get the new user id
        $userID = $this->db->conn->lastInsertId();

        // Set session variables
        Session::set('user', [
            'id' => $userID,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
        ]);

        redirect('/');
    }

    /**
     * Logout a user.
     *
     * @return void
     */
    public function logout()
    {
        Session::clearAll();

        // Unset session cookie
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain']);

        redirect('/');
    }

    /**
     * Authenticate a user.
     *
     * @return void
     */
    public function authenticate()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $params = [
            'email' => $email,
        ];

        $errors = [];

        // Validation
        // Email
        if (! Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        // Password
        if (! Validation::string($password, 6)) {
            $errors['password'] = 'Password must at least 6 chacracters.';
        }

        if (! empty($errors)) {
            $data = [
                'errors' => $errors,
            ];
            loadView('users/login', $data);

            return;
        }

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if (! $user) {
            $errors['email'] = 'Incorrect credentials.';
            $data = [
                'errors' => $errors,
            ];
            loadView('users/login', $data);

            return;
        }

        if (! password_verify($password, $user->password)) {
            $errors['password'] = 'Incorrect credentials.';
            $data = [
                'errors' => $errors,
            ];
            loadView('users/login', $data);

            return;
        }

        // Set session variables
        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state,
        ]);

        redirect('/');
    }
}
