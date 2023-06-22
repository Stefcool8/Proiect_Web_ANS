<?php

namespace App\controllers;

use App\utils\JWT;
use App\utils\ResponseHandler;
use App\utils\Database;
use Exception;


class UserController extends Controller {


    public function create()
    {
        // get the request body
        $body = json_decode(file_get_contents('php://input'), true);

        $payload = $this->getPayload();
        if ($payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'You can not create a new user while logged in'
            ]);
            exit;
        }

        // validate the request body
        if (!isset($body['firstName']) || !isset($body['lastName']) || !isset($body['email']) || !isset($body['password']) || !isset($body['username'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body.']);
            exit;
        }

        try {
            $db = Database::getInstance();

            $existingUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $body['username']]);

            // check if username exists
            if ($existingUser) {
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "Username already exists"]);
                exit;
            }

            $existingUser = $db->fetchOne("SELECT * FROM user WHERE email = :email", ['email' => $body['email']]);

            // check if email exists
            if ($existingUser) {
                ResponseHandler::getResponseHandler()->sendResponse(488, ["error" => "Email already exists"]);
                exit;
            }

            // create the user
            $db->insert('user', [
                'firstName' => $this->sanitizeData($body['firstName']), // sanitize the data (remove html tags etc
                'lastName' => $this->sanitizeData($body['lastName']),
                'password' => password_hash($this->sanitizeData($body['password']), PASSWORD_DEFAULT),
                'email' => $this->sanitizeData($body['email']),
                'username' => $this->sanitizeData($body['username']),
                'isAdmin' => false,
                'uuid' => uniqid()
            ]);

            ResponseHandler::getResponseHandler()->sendResponse(200, ["message" => "User created successfully"]);
        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => $e->getMessage()]);
        }
    }


    public function delete($uuid) {
        $payload = $this->getPayload();

        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", [
                'username' => $this->sanitizeData($payload['username'])
            ]);

            if ($currentUser['isAdmin'] || (($currentUser['uuid'] == $uuid) && $user)) {
                $db->delete('user', ['uuid' => $uuid]);
                ResponseHandler::getResponseHandler()->sendResponse(
                    204, ['message' => 'User deleted successfully']
                );
                exit;
            }

            if (!$user) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
                exit;
            }
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }


    public function get($uuid) {
        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", [
                'username' => $this->sanitizeData($payload['username'])
            ]);

            if ($currentUser['isAdmin'] || (($currentUser['uuid'] == $uuid) && $user)) {
                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => [
                        'uuid' => $this->sanitizeData($user['uuid']),
                        'isAdmin' => $user['isAdmin'],
                        'firstName' => $this->sanitizeData($user['firstName']),
                        'lastName' => $this->sanitizeData($user['lastName']),
                        'email' => $this->sanitizeData($user['email']),
                        'username' => $this->sanitizeData($user['username']),
                        'bio' => $this->sanitizeData($user['bio'])
                    ]
                ]);
                exit;
            }

            if (!$user) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
                exit;
            }

            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);

        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }


    public function update($uuid) {
        $payload = $this->getPayload();

        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", [
                'username' => $this->sanitizeData($payload['username'])
            ]);

            if (!$user || !$currentUser) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
                exit;
            }

            if (!$currentUser['isAdmin'] && (($currentUser['uuid'] != $uuid))) {
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
                exit;
            }

        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // verify if the required fields are present
        if (empty($data['firstName']) || empty($data['lastName']) || empty($data['username']) || empty($data['email'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Missing required fields']);
            exit;
        }

        // verify if the username and email are not already taken
        $user = $db->fetchOne("SELECT * FROM user WHERE username = :username", [
            'username' => $this->sanitizeData($data['username'])
        ]);

        if ($user && $user['uuid'] != $uuid) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Username already exists']);
            exit;
        }

        $user = $db->fetchOne("SELECT * FROM user WHERE email = :email", [
            'email' => $this->sanitizeData($data['email'])
        ]);
        if ($user && $user['uuid'] != $uuid) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Email already exists']);
            exit;
        }

        $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);

        // get the data
        $firstName = $this->sanitizeData($data['firstName']);
        $lastName = $this->sanitizeData($data['lastName']);
        $username = $this->sanitizeData($data['username']);
        $email = $this->sanitizeData($data['email']);
        $bio = $this->sanitizeData($data['bio']);

        // update the user
        $db->update('user', [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'username' => $username,
            'email' => $email,
            'bio' => $bio,
        ], ['uuid' => $uuid]);

        $token = JWT::getJWT()->encode([
            'username' => $username,
            'isAdmin' => $user['isAdmin'],
            'exp' => time() + 3600
        ]);

        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'message' => 'User updated successfully',
            'token' => $token,
            "user" => [
                "username" => $username,
                "uuid" => $uuid,
                "isAdmin" => $user['isAdmin'],
            ]
        ]);
    }

    public function gets() {
        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        if (!$payload['isAdmin']) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();

            $users = $db->fetchAll("SELECT * FROM user ORDER BY id DESC");

            if (empty($users)) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'Users not found']);
                exit;
            }

            $userArray = [];
            foreach ($users as $user) {
                $userArray[] = [
                    'isAdmin' => $user['isAdmin'],
                    'uuid' => $user['uuid'],
                    'firstName' => $this->sanitizeData($user['firstName']),
                    'lastName' => $this->sanitizeData($user['lastName']),
                    'email' => $this->sanitizeData($user['email']),
                    'username' => $this->sanitizeData($user['username']),
                ];
            }

            ResponseHandler::getResponseHandler()->sendResponse(200, ['data' => $userArray]);

        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

    public function getByInterval($startPage) {
        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();

            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", [
                'username' => $this->sanitizeData($payload['username'])
            ]);

            $startIndex = $startPage - 1; // The start index of the projects
            $pageSize = 4; // The number of projects to retrieve per page

            // Calculate the offset based on the start index and page size
            $offset = $startIndex * $pageSize;
            // fetch all projects for this user
            //$projects = $db->fetchAll("SELECT * FROM project WHERE uuidUser = :uuidUser", ['uuidUser' => $uuid]);

            // $projects = $db->fetchAll("SELECT * FROM project WHERE uuidUser = :uuidUser LIMIT ".$pageSize,
            //  ['uuidUser' => $uuid]);

            $users = $db->fetchAll("SELECT * FROM user LIMIT " . $pageSize . " OFFSET " . $offset);

            // if there are no projects, return a message indicating this
            if (!$users) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'No users found']);
                exit;
            }

            if ($payload['isAdmin']) {

                // build the project data for the response
                $userData = [];
                foreach ($users as $user) {
                    $userData[] = [
                        'isAdmin' => $user['isAdmin'],
                        'uuid' => $user['uuid'],
                        'firstName' => $user['firstName'],
                        'lastName' => $user['lastName'],
                        'email' => $user['email'],
                        'username' => $user['username']
                    ];
                }

                ResponseHandler::getResponseHandler()->sendResponse(200, ['users' => $userData]);
            } else {
                ResponseHandler::getResponseHandler()->sendResponse(401, [
                    'error' => 'Unauthorized'
                ]);
                exit;
            }
        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

}
