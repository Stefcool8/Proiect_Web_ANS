<?php
// DONE
namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;
use App\Utils\Database;

/** 
 * Controller for User operations.
 * 
 */
class UserController {

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
     *             @OA\Property(property="name", type="string", example="John Doe"),
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
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status_code", type="integer", example=500),
     *              @OA\Property(property="error", type="string", example="Internal Server Error")
     *         )
     *    )
     * )
     */
    public function create() {
        // get the request body
        $body = json_decode(file_get_contents('php://input'), true);

        // validate the request body
        if (!isset($body['name']) || !isset($body['email']) || !isset($body['password']) || !isset($body['username'])) {
            return ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body.']);
        }

        try {
            // Check if username exists
            $db = Database::getInstance();
            $existingUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $body['username']]);
            
            if ($existingUser) {
                return ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "Username already exists"]);
            }

            // create the user
            $db->insert('user', [
                'isAdmin' => 0,
                'name' => $body['name'],
                'password' => password_hash($body['password'], PASSWORD_DEFAULT),
                'email' => $body['email'],
                'username' => $body['username'],
                'uuid' => uniqid()
            ]);

            // send the data
            return ResponseHandler::getResponseHandler()->sendResponse(200, ["message" => "User created successfully"]);
        } catch (\Exception $e) {
            // Handle potential exception during database insertion
            return ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
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
     *             type="string"
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
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);

            if (!$user) {
                return ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
            }

            // TODO: Verify if the user has the correct permissions to delete this user
            // If not, return ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);

            $db->delete('user', ['uuid' => $uuid]);

            return ResponseHandler::getResponseHandler()->sendResponse(204, ['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            // Handle potential exception during database deletion
            return ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

    /**
 * @OA\Get(
 *     path="/api/user/{uuid}",
 *     summary="Retrieve user information",
 *     operationId="getUser",
 *     tags={"User"},
 * 
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         description="UUID of the user to retrieve",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(response="200", description="User found", @OA\JsonContent(
 *         @OA\Property(property="data", type="object",
 *             @OA\Property(property="isAdmin", type="boolean"),
 *             @OA\Property(property="uuid", type="string"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="username", type="string")
 *         )
 *     )),
 *     @OA\Response(
 *         response="404",
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=404),
 *             @OA\Property(property="error", type="string", example="User not found")
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
    public function get($uuid) {
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);

            if (!$user) {
                return ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
            }

            return ResponseHandler::getResponseHandler()->sendResponse(200, [
                'data' => [
                    'isAdmin' => $user['isAdmin'],
                    'uuid' => $user['uuid'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'username' => $user['username']
                ]
            ]);

        } catch (\Exception $e) {
            // Handle potential exception during database deletion
            return ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

}
