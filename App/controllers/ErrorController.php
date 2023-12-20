<?php

namespace App\Controllers;

class ErrorController
{

    /**
     * Show the 404 page.
     *
     * @param string $message
     * @return void
     */
    public static function notFound($message = "Page not found.")
    {
        http_response_code(404);

        $data = [
            'message' => $message,
            'status' => '404'
        ];
        loadView('error', $data);
    }

    /**
     * Show the 403 page.
     *
     * @param string $message
     * @return void
     */
    public static function unauthorized($message = "You are not authorized to view this page.")
    {
        http_response_code(403);

        $data = [
            'message' => $message,
            'status' => '403'
        ];
        loadView('error', $data);
    }
}
