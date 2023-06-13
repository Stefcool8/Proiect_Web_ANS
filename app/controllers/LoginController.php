<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
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

        $database = \App\Utils\Database::getInstance();

        // fetch user record
        $user = $database->fetchOne('SELECT * FROM user WHERE username = :username', ['username' => $username]);

        if (!$user) {
            ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'Wrong username']);
            return;
        }

        if (!password_verify($password, $user['password'])) {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Wrong password']);
            return;
        }

        // if valid, create and return a JWT token
        $payload = ['username' => $username, 'exp' => time() + 3600]; 
        $token = \App\Utils\JWT::encode($payload);

        // prepare user data to return
        $userData = [
            'username' => $user['username'],
            'uuid' => $user['uuid']
        ];

        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'user' => $userData,
            'token' => $token,
        ]);
        return;
    }

}