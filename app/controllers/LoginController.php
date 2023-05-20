<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;


class LoginController {

    /**
     * @OA\Get(
     *     path="/api/login",
     *     @OA\Response(response="200", description="This method returns the data for the login page.")
     * )
     */
    public function get() {
        // send the view
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Login Form',
        ]);
    }
}