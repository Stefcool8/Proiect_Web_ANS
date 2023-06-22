<?php

namespace App\controllers;

use App\utils\ResponseHandler;
use App\utils\JWT;
use InvalidArgumentException;
use App\utils\Database;


class AuthController extends Controller {


    public function get() {
        
        // get the authorization field from the request header
        $headers = apache_request_headers();

        // if the authorization field is not set, return an error
        if (!isset($headers['Authorization']) || substr($headers['Authorization'], 0, 7) !== 'Bearer ') {
            ResponseHandler::getResponseHandler()->sendResponse(400, [
                'error' => 'Bad request'
            ]);
            return;
        }

        // extract the token from the authorization field
        $authHeader = $headers['Authorization'];
        $token = substr($authHeader, 7);

        try {
            // decode the token
            JWT::getJWT()->decode($token);
        } catch (InvalidArgumentException $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }

        // if the token is valid, return a success message
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'message' => 'Authorized'
        ]);
    }

    public function getAdmin()
    {
        $payload = $this->getPayload();
        if (!$payload['isAdmin']) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }
        try {
            $db = Database::getInstance();
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            if (!$currentUser) {
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
                return;
            }

            ResponseHandler::getResponseHandler()->sendResponse(200, [
                'data' => [
                    'isAdmin' => $payload['isAdmin'],
                    'username' => $payload['username'],
                    'uuid' => $currentUser['uuid']
                ]
            ]);
        }catch (InvalidArgumentException $e) {
        ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
    }

    }



    public function verifyAccess()
    {

        $body = json_decode(file_get_contents('php://input'), true);
        $uuid = $body['uuid'];
        // get the token from the request header
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // decode the token
            $payload = JWT::getJWT()->decode($token);
        } catch (InvalidArgumentException $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        if (!$payload['isAdmin']) {

            $db = Database::getInstance();
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);
            /*ResponseHandler::getResponseHandler()->sendResponse(200, [
                'data' => [
                    'title' => 'DB',
                    'isAdmin' => $currentUser['isAdmin'],
                    'username' =>$currentUser['username'],
                    'CurrentUserUUID' =>$currentUser['uuid'],
                    'uuid' => $uuid,
                ]
            ]);
            */
            if ($currentUser['uuid'] != $uuid) {
                ResponseHandler::getResponseHandler()->sendResponse(401, [
                    'error' => 'Unauthorized'
                ]);
            } else {
                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => [
                        'title' => 'HELLo',
                        'isAdmin' => $payload['isAdmin'],
                        'username' => $payload['username']
                    ]
                ]);
            }
            return;
        }
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'data' => [
                'title' => 'Admin',
                'isAdmin' => $payload['isAdmin'],
                'username' => $payload['username']
            ]
        ]);
    }

}
