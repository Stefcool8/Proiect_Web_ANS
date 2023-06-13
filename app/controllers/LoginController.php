<?php

namespace App\Controllers;

use App\Utils\Database;
use App\Utils\JWT;
use App\Utils\ResponseHandler;

class LoginController {

    /**
     * @OA\Get(
     *     path="/api/login",
     *     tags={"Login"},
     *     summary="Get Login Form Data",
     *     description="This endpoint is used to get the login form details.",
     *     @OA\Response(
     *         response=200,
     *         description="Success response with login form data.",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="title", type="string", example="Login Form", description="The title of the login form.")
     *         )
     *     )
     * )
     */
    public function get() {
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Login',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login user",
     *     tags={"Login"},
     *     @OA\RequestBody(
     *         description="User login credentials",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"username", "password"},
     *                 @OA\Property(
     *                     property="username",
     *                     description="The user's username",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="The user's password",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     description="The logged in user",
     *                     type="object",
     *                     @OA\Property(
     *                         property="username",
     *                         description="The username of the logged in user",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="uuid",
     *                         description="The uuid of the logged in user",
     *                         type="string"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     description="The JWT token",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *        response=400,
     *        description="Bad Request. Missing username or password",
     *        @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. Wrong password",
     *         @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found. Wrong username",
     *         @OA\MediaType(mediaType="application/json")
     *     )
     * )
     */
    public function login() {
        $body = json_decode(file_get_contents('php://input'), true);

        $username = $body['username'];
        $password = $body['password'];

        if (!$username || !$password) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Missing username or password']);
            return;
        }

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