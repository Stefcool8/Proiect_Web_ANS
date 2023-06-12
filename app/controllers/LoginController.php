<?php

namespace App\Controllers;

use App\Utils\Database;
use App\Utils\JWT;
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

    public function login() {
        $body = json_decode(file_get_contents('php://input'), true);

        $username = $body['username'];
        $password = $body['password'];

        $database = Database::getInstance();

        // Fetch user record
        $user = $database->fetchOne('SELECT * FROM user WHERE username = :username', ['username' => $username]);

        // Check if user exists
        if ($user) {
            // Check if password is valid
            if (password_verify($password, $user['password'])) {
                // If valid, create and return a JWT token
                $payload = ['username' => $username, 'exp' => time() + 3600];  // Expires in 1 hour

                $token = JWT::encode($payload);

                // Prepare user data to return
                $userData = [
                    'username' => $user['username'],
                    'uuid' => $user['uuid']
                    // include any other user data you want to return
                ];

                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'user' => $userData,
                    'token' => $token,
                ]);
            } else {
                // If password is not valid, return an error
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Invalid password']);
            }
        } else {
            // If user doesn't exist, return an error
            ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
        }
    }

}