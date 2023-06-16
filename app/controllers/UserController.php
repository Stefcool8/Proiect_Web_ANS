<?php
// DONE
namespace App\controllers;

use App\utils\ResponseHandler;
use App\utils\Database;
use Exception;

/** 
 * Controller for User operations
 * 
 */
class UserController extends Controller {

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
     *         response=488,
     *         description="Email already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=488),
     *             @OA\Property(property="error", type="string", example="Email already exists")
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

        $payload = $this->getPayload();
        if($payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'You can not create a new player before logging out.'
            ]);
            exit;
        }

        // validate the request body
        if (!isset($body['firstName']) || !isset($body['lastName']) || !isset($body['email']) || !isset($body['password']) || !isset($body['username'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body.']);
            exit;
        }

        try {
            $db = Database::getInstance();

            $existingUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $body['username']]);

            // check if username exists
            if ($existingUser) {
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "Username already exists"]);
                exit;
            }

            $existingUser = $db->fetchOne("SELECT * FROM user WHERE email = :email", ['email' => $body['email']]);

            // check if email exists
            if ($existingUser) {
                ResponseHandler::getResponseHandler()->sendResponse(488, ["error" => "Email already exists"]);
                exit;
            }

            // create the user
            $db->insert('user', [
                'firstName' => $body['firstName'],
                'lastName' => $body['lastName'],
                'password' => password_hash($body['password'], PASSWORD_DEFAULT),
                'email' => $body['email'],
                'username' => $body['username'],
                'isAdmin' => true,
                'uuid' => uniqid()
            ]);

            ResponseHandler::getResponseHandler()->sendResponse(200, ["message" => "User created successfully"]);
        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => $e->getMessage()]);
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
        $payload = $this->getPayload();
        if(!$payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username",['username' => $payload['username']]);



            if($currentUser['isAdmin'] || (($currentUser['uuid'] == $uuid) && $user)){
                $db->delete('user', ['uuid' => $uuid]);
                ResponseHandler::getResponseHandler()->sendResponse(
                    204, ['message' => 'User deleted successfully']
                );
                exit;
            }

            if (!$user) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
                exit;
            }
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }


    /**
     * @OA\Get(
     *     path="/user/{uuid}",
     *     summary="Get user information",
     *     description="Get the details of a user by UUID.",
     *     operationId="getUser",
     *     tags={"user"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 example={
     *                     "firstName": "John",
     *                     "lastName": "Doe",
     *                     "email": "john.doe@example.com",
     *                     "username": "johndoe",
     *                     "uuid": "123e4567-e89b-12d3-a456-426614174000",
     *                     "isAdmin": false,
     *                     "bio": "This is John Doe's bio."
     *                 }
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Internal Server Error"),
     *         ),
     *     ),
     * )
     */
    public function get($uuid) {
        $payload = $this->getPayload();
        if(!$payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username",['username' => $payload['username']]);

            if($currentUser['isAdmin'] || (($currentUser['uuid'] == $uuid) && $user)){
                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => [
                        'uuid' => $user['uuid'],
                        'isAdmin' => $user['isAdmin'],
                        'firstName' => $user['firstName'],
                        'lastName' => $user['lastName'],
                        'email' => $user['email'],
                        'username' => $user['username']
                    ]
                ]);
                exit;
            }

            if (!$user) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
                exit;
            }

            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);


        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

    public function update($uuid)
    {

        $payload = $this->getPayload();
        if(!$payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT * FROM user WHERE uuid = :uuid", ['uuid' => $uuid]);
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            if(!$currentUser['isAdmin'] && (($currentUser['uuid'] != $uuid) && $user)){
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
                exit;
            }

            if (!$user) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
                exit;
            }



        } catch (Exception $e) {
            // Handle potential exception during database handling
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Verify if the required fields are present
        if (empty($data['firstName']) || empty($data['lastName']) || empty($data['username']) || empty($data['email'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Missing required fields']);
            exit;
        }

        // Verify if the username and email are not already taken
        $user = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $data['username']]);
        if ($user && $user['uuid'] != $uuid) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Username already exists']);
            exit;
        }
        $user = $db->fetchOne("SELECT * FROM user WHERE email = :email", ['email' => $data['email']]);
        if ($user && $user['uuid'] != $uuid) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Email already exists']);
            exit;
        }

        // Get the data
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $username = $data['username'];
        $email = $data['email'];

        // Update the user
        $db->update('user', [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'username' => $username,
            'email' => $email
        ], ['uuid' => $uuid]);

        ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'User updated successfully']);
    }
}
