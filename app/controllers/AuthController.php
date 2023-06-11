<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;
use App\Utils\JWT;

/**
 * Controller for user authentication
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
                'error' => 'Bad request'
            ]);
            return;
        }

        // extract the token from the authorization field
        $authHeader = $headers['Authorization'];
        $token = substr($authHeader, 7);

        try {
            // decode the token
            $payload = JWT::getJWT()->decode($token);
        } catch (\InvalidArgumentException $e) {
            // if the token is invalid, return an error
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
}
