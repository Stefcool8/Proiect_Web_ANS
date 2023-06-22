<?php

namespace App\controllers;

use App\utils\Database;
use App\utils\JWT;
use App\utils\ResponseHandler;

class LoginController extends Controller {



    public function login() {
        $body = json_decode(file_get_contents('php://input'), true);

        $username = $this->sanitizeData($body['username']);
        $password = $this->sanitizeData($body['password']);

        if (!$username || !$password) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Missing username or password']);
            return;
        }

        $database = Database::getInstance();

        // fetch user record
        $user = $database->fetchOne('SELECT * FROM user WHERE username = :username', ['username' => $username]);

        // check if user exists
        if ($user) {
            // check if password is valid
            if (password_verify($password, $user['password'])) {
                // if valid, create and return a JWT token
                $payload = ['username' => $username, 'isAdmin' => $user['isAdmin'], 'exp' => time() + 3600];

                $token = JWT::encode($payload);

                // prepare user data to return
                $userData = [
                    'username' => $user['username'],
                    'uuid' => $user['uuid'],
                    'isAdmin' => $user['isAdmin']
                ];

                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'user' => $userData,
                    'token' => $token,
                ]);
            } else {
                // if password is not valid, return an error
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Invalid password']);
            }
        } else {
            // if user doesn't exist, return an error
            ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
        }
    }
}