<?php
// DONE
namespace App\Controllers;

use App\Utils\ResponseHandler;
use App\Utils\JWT;
use InvalidArgumentException;

/**
 * Controller for the Dashboard page
 * 
 */
class DashboardController {

    /**
     * @OA\Get(
     *     path="/api/dashboard",
     *     security={{"bearerAuth":{}}},
     *     summary="Retrieve the dashboard page data for the authenticated user",
     *     operationId="getDashboard",
     *     tags={"Dashboard"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns the user dashboard data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Dashboard"),
     *                 @OA\Property(property="username", type="string", example="user"),
     *                 @OA\Property(property="isAdmin",type="boolean", example ="false")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
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
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
            exit;
        }

        try {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            
            // decode the token
            $payload = JWT::getJWT()->decode($token);
            // send the data
            ResponseHandler::getResponseHandler()->sendResponse(200, [
                'data' => [
                    'title' => 'Dashboard',
                    'username' => $payload['username'],
                    'isAdmin' => $payload['isAdmin']
                ]
            ]);
        } catch (InvalidArgumentException $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
        }
    }
}
