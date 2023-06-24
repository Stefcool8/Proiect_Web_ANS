<?php

namespace App\controllers;

use App\utils\Database;
use App\utils\JWT;
use App\utils\ResponseHandler;

class LoginController extends Controller {

    /**
     * @OA\Get(
     *     path="/api/login",
     *     tags={"Login"},
     *     summary="Retrieve page title",
     *     description="This endpoint is used to get the login page title.",
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
     *                     type="string",
     *                     example="johnDoe"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="The user's password",
     *                     type="string",
     *                     example="SecureP@ssword123"
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
     *                         type="string",
     *                         example="johnDoe"
     *                     ),
     *                     @OA\Property(
     *                         property="uuid",
     *                         description="The uuid of the logged in user",
     *                         type="string",
     *                         example="648c882816eda"
     *                     ),
     *                     @OA\Property (
     *                         property="isAdmin",
     *                         description="The isAdmin of the logged in user",
     *                         type="boolean",
     *                         example=false
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     description="The JWT token",
     *                     type="string",
     *                     example="johnDoe_JWT"
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

        if (!isset($body['username']) || !isset($body['password'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Missing username or password']);
            return;
        }
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