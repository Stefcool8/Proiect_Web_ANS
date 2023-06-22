<?php

namespace App\controllers;

use App\utils\JWT;
use App\utils\ResponseHandler;
use App\utils\Database;
use Exception;

/**
 * Controller for User operations
 *
 */
class UserController extends Controller {

    /**
     * @OA\Post(
     *     path="/api/user",
     *     summary="Create a new user",
     *     operationId="createUser",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         description="User data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="firstName", type="string", example="John"),
     *             @OA\Property(property="lastName", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="SecureP@ssword123"),
     *             @OA\Property(property="username", type="string", example="johnDoe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="User created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request body",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=400),
     *             @OA\Property(property="error", type="string", example="Invalid request body")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Username already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=409),
     *             @OA\Property(property="error", type="string", example="Username already exists")
     *         )
     *     ),
     *     @OA\Response(
     *         response=488,
     *         description="Email already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=488),
     *             @OA\Property(property="error", type="string", example="Email already exists")
     *         )
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status_code", type="integer", example=500),
     *              @OA\Property(property="error", type="string", example="Internal Server Error")
     *         )
     *    )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/user/{uuid}",
     *     summary="Delete a user",
     *     operationId="deleteUser",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the user to be deleted",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="648c882816eda"
     *         )
     *     ),
     *     @OA\Response(response="204", description="User successfully deleted."),
     *     @OA\Response(
     *         response="404",
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Internal Server Error")
     *        )
     *    )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/user/{uuid}",
     *     summary="Get user information",
     *     description="Get the details of a user by UUID.",
     *     operationId="getUser",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the user to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="648c882816eda"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 example={
     *                     "uuid": "648c882816eda",
     *                     "isAdmin": false,
     *                     "firstName": "John",
     *                     "lastName": "Doe",
     *                     "email": "john.doe@example.com",
     *                     "username": "johndoe",
     *                     "bio": "This is John Doe's bio."
     *                 }
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Internal Server Error"),
     *         ),
     *     ),
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/user/{uuid}",
     *     summary="Update user information",
     *     description="This can only be done by the logged in user or an admin.",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="UUID of user to update",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="648c882816eda"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Updated user object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="firstName",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="lastName",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email"
     *                 ),
     *                 @OA\Property(
     *                     property="bio",
     *                     type="string"
     *                 ),
     *                 example={
     *                     "firstName": "John",
     *                     "lastName": "Doe",
     *                     "username": "johndoe",
     *                     "email": "john.doe@example.com",
     *                     "bio": "Software Engineer"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="message",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(
     *                         property="username",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="uuid",
     *                         type="string",
     *                         format="uuid"
     *                     ),
     *                     @OA\Property(
     *                         property="isAdmin",
     *                         type="boolean"
     *                     )
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid username or email supplied or missing required fields"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Retrieve list of users",
     *     operationId="getUsers",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status_code",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="isAdmin",
     *                         type="boolean",
     *                         example="false"
     *                     ),
     *                     @OA\Property(
     *                         property="uuid",
     *                         type="string",
     *                         example="648c882816eda"
     *                     ),
     *                     @OA\Property(
     *                         property="firstName",
     *                         type="string",
     *                         example="Doe"
     *                     ),
     *                     @OA\Property (
     *                         property="lastName",
     *                         type="string",
     *                         example="John"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="johndoe@example.com"
     *                     ),
     *                     @OA\Property(
     *                         property="username",
     *                         type="string",
     *                         example="johnDoe"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Users not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Users not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Internal Server Error"
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/user/page/{startPage}",
     *     summary="Retrieve users by page",
     *     operationId="getUsersByInterval",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="startPage",
     *         in="path",
     *         description="Page number to start from",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="users",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="isAdmin",
     *                         type="boolean",
     *                         example=true
     *                     ),
     *                     @OA\Property(
     *                         property="uuid",
     *                         type="string",
     *                         example="648c882816eda"
     *                     ),
     *                     @OA\Property(
     *                         property="firstName",
     *                         type="string",
     *                         example="John"
     *                     ),
     *                     @OA\Property(
     *                         property="lastName",
     *                         type="string",
     *                         example="Doe"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="johndoe@example.com"
     *                     ),
     *                     @OA\Property(
     *                         property="username",
     *                         type="string",
     *                         example="johnDoe"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No users found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="No users found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Internal Server Error"
     *             )
     *         )
     *     )
     * )
     */

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
