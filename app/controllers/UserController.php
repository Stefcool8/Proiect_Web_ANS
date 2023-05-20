<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;
use App\Utils\Database;

class UserController {

    /**
     * @OA\Post(
     *     path="/api/user",
     *     @OA\Response(response="200", description="This method returns the data for the home page.")
     * )
     */
    public function create() {
        // get the request body
        $body = json_decode(file_get_contents('php://input'), true);

        // validate the request body
        if (!isset($body['name']) || !isset($body['email']) || !isset($body['password'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, [
                'error' => 'Invalid request body.'
            ]);
        }

        // create the user
        $db = Database::getInstance();
        $db->insert('user', [
            'name' => $body['name'],
            'password_hash' => password_hash($body['password'], PASSWORD_DEFAULT),
            'username' => $body['username'],
            'api_key' => uniqid()
        ]);


        // send the data
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Open source tool for data visualization',
            'body' => 'Successfully created the user.'
        ]);

    }
}
