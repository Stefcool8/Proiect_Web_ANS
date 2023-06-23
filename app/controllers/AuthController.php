<?php

namespace App\controllers;

use App\utils\ResponseHandler;
use App\utils\JWT;
use InvalidArgumentException;
use App\utils\Database;

/**
 * Controller for user authentication
 *
 */
class AuthController extends Controller {

    /**
     * @OA\Get(
     *     path="/api/auth",
     *     summary="Validate the user's token",
     *     operationId="getAuth",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="The user's token is valid.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Authorized")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Authorization header is missing or malformed.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status_code", type="integer", example=400),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="error", type="string", example="Bad request")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="The user's token is invalid.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="error", type="string", example="Unauthorized")
     *             )
     *         )
     *     )
     * )
     */
    public function get() {
        // get the authorization field from the request header
        $headers = apache_request_headers();

        // if the authorization field is not set, return an error
        if (!isset($headers['Authorization']) || substr($headers['Authorization'], 0, 7) !== 'Bearer ') {
            ResponseHandler::getResponseHandler()->sendResponse(400, [
                'error' => 'Unauthorized'
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
                'error' => 'Bad request'
            ]);
            return;
        }

        // if the token is valid, return a success message
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'message' => 'Authorized'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/admin",
     *     operationId="verifyAdmin",
     *     tags={"Authentication"},
     *     summary="Validate the admin's token and getting some information about admin",
     *     description="Endpoint for verifying admin privileges.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="isAdmin", type="boolean"),
     *                 @OA\Property(property="username",type="string"),
     *                 @OA\Property(property="uuid", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Not admin",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Not admin")
     *         )
     *     )
     * )
     */
    public function getAdmin() {
        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }
        if (!$payload['isAdmin']) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Not admin'
            ]);
            return;
        }
        try {
            $db = Database::getInstance();

            $existingUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            if (!$existingUser) {
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "Uuid assigned does not exist"]);
                return;
            }
        } catch (Exception $e) {
            // Handle potential exception during database connection
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            return;
        }

        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'data' => [
                'isAdmin' => $payload['isAdmin'],
                'username' => $payload['username'],
                'uuid' => $existingUser['uuid']
            ]
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/auth/verifyAccess",
     *     operationId="verifyAccess",
     *     tags={"Authentication"},
     *     summary="Verify access based on token",
     *     description="Verify access based on token",
     *     security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *         description="Verify Access form data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"uuid"},
     *                 @OA\Property(
     *                     property="uuid",
     *                     description="The uuid of the user",
     *                     type="string",
     *                     example="648c882816eda"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="isAdmin", type="boolean"),
     *                 @OA\Property(property="username",type="string"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function verifyAccess()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        if (!isset($body['uuid'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, [
                'error' => 'Bad request'
            ]);
            return;
        }

        $uuid = $body['uuid'];

        try {
            $db = Database::getInstance();

            $existingUser = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);

            if (!$existingUser) {
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "Uuid assigned does not exist"]);
                return;
            }
        } catch (Exception $e) {
            // Handle potential exception during database connection
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            return;
        }

        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }
        if (!$payload['isAdmin']) {
            $db = Database::getInstance();
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);


            if ($currentUser['uuid'] != $uuid) {
                ResponseHandler::getResponseHandler()->sendResponse(401, [
                    'error' => 'Unauthorized'
                ]);
                return;
            } else {
                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => [
                        'isAdmin' => $payload['isAdmin'],
                        'username' => $payload['username']
                    ]
                ]);
            }
            return;
        }
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'data' => [
                'isAdmin' => $payload['isAdmin'],
                'username' => $payload['username']
            ]
        ]);
    }
}
