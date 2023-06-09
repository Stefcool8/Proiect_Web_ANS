<?php
// DONE
namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;
use App\Utils\JWT;

/**
 * Controller for the user authentication.
 *
 */
class AuthController {

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
        
        // get the token from the request header
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // decode the token
            $payload = JWT::getJWT()->decode($token);
        } catch (\InvalidArgumentException $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
        }

        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'message' => 'Authorized'
        ]);
    }


   /**
 * This is the OpenAPI documentation for the verifyAdmin() function.
 *
 * @OA\Get(
 *     path="/api/auth/admin",
 *     operationId="verifyAdmin",
 *     tags={"Authentication"},
 *     summary="Validate the admin's token",
 *     description="Endpoint for verifying admin privileges.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="title", type="string", example="Admin"),
 *                 @OA\Property(property="isAdmin", type="boolean"),
 *                 @OA\Property(property="username",type="string")
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

    public function getAdmin() {
        
        // get the token from the request header
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // decode the token
            $payload = JWT::getJWT()->decode($token);
        } catch (\InvalidArgumentException $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
        }
        if (!$payload['isAdmin']) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
        }
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'data' => [
                'title' => 'Admin',
                'isAdmin' => $payload['isAdmin'],
                'username' =>$payload['username']
            ]
        ]);
    }
}