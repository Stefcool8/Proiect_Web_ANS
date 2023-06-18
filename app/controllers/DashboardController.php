<?php
// DONE
namespace App\controllers;

use App\utils\ResponseHandler;
use App\utils\JWT;
use InvalidArgumentException;
use App\Utils\Database;

/**
 * Controller for the Dashboard page
 * 
 */
class DashboardController extends Controller {

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
     *                 @OA\Property(property="isAdmin",type="boolean", example ="false"),
     *                 @OA\Property(property="uuid",type="string", example ="userId")
     *                  
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

        $payload = $this->getPayload();
        if(!$payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
            return;
        }
        try {
            $db = Database::getInstance();
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);
            
            ResponseHandler::getResponseHandler()->sendResponse(200, [
                'data' => [
                    'title' => 'Dashboard',
                    'username' => $payload['username'],
                    'isAdmin' => $currentUser['isAdmin'],
                    'uuid' => $currentUser['uuid']
                ]
            ]);
        } catch (InvalidArgumentException $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
        }
    }
}
