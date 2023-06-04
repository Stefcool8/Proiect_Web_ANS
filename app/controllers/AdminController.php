<?php
// DONE
namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;
use App\Utils\JWT;
use App\Utils\Database;

/**
 * Controller for the Admin page.
 * 
 */
class AdminController {

    /**
     * @OA\Get(
     *     path="/api/admin",
     *     summary="Retrieve admin page data",
     *     operationId="getAdmin",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status_code",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Admin Page"
     *                 )
     *             )
     *         )
     *     )
     * )
     * 
     * @OA\Get(
    *     path="/api/admin/users",
    *     summary="Retrieve list of users",
    *     operationId="getUsers",
    *     tags={"Admin"},
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
    *                         property="uuid",
    *                         type="string",
    *                         example="user-123"
    *                     ),
    *                     @OA\Property(
    *                         property="name",
    *                         type="string",
    *                         example="John Doe"
    *                     ),
    *                     @OA\Property(
    *                         property="email",
    *                         type="string",
    *                         example="johndoe@example.com"
    *                     ),
    *                     @OA\Property(
    *                         property="username",
    *                         type="string",
    *                         example="johndoe"
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
    public function getInfo() {
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Admin Page',
        ]);
    }

    public function getUsers(){
        try {
            $db = Database::getInstance();

            $users = $db->fetchAll("SELECT * FROM user ");

            if (empty($users)) {
                return ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'Users not found']);
            }

            $userArray = [];
            foreach ($users as $user) {
                $userArray[] = [
                    'isAdmin' => $user['isAdmin'],
                    'uuid' => $user['uuid'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'username' => $user['username']
                ];
            }

            return ResponseHandler::getResponseHandler()->sendResponse(200, ['data' => $userArray]);

        } catch (\Exception $e) {
            // Handle potential exception during database deletion
            return ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

}