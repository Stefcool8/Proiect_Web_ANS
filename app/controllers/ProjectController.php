<?php
// DONE
namespace App\Controllers;

use App\Utils\ResponseHandler;
use App\Utils\Database;
use App\Utils\JWT;
use Exception;


/** 
 * Controller for Project operations
 * 
 */

 class ProjectController {

     /**
     * @OA\Post(
     *     path="/api/project",
     *     summary="Create a new project",
     *     operationId="createProject",
     *     tags={"Project"},
     *     @OA\RequestBody(
     *         description="Project data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="project"),
     *             @OA\Property(property="chart", type="string", example="example Chart"),
     *             @OA\Property(property="uuidUser", type="string", example="uuidExample")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Project created successfully")
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
     *         description="Project already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=409),
     *             @OA\Property(property="error", type="string", example="Project already exists")
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
        
         
        //$uuid = $body['uuid'];
        // get the token from the request header
        $payload = $this->getPayload();

        try {
            $db = Database::getInstance();

            $existingUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            if(!$existingUser){
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "User assigned does not exist"]);
                exit;
            }

            $uuidUser = $existingUser['uuid']; 
           
        } catch (Exception $e) {
            // Handle potential exception during database insertion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            exit;
        }


        // validate the request body
        if (!isset($body['name']) || !isset($body['chart'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body.']);
            exit;
        }

        try {
            $db = Database::getInstance();

            $existingProject = $db->fetchOne("SELECT * FROM project WHERE name = :name AND uuidUser = :uuidUser", ['name' => $body['name'],'uuidUser' =>$uuidUser]);

            // check if project exists
            if ($existingProject) {
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "Porject already exists"]);
                exit;
            }

            // create the project
            $db->insert('project', [
                'name' => $body['name'],
                'chart' => $body['chart'],
                'uuidUser' => $uuidUser,
                'uuid' => uniqid()
            ]);

            // send the data
            ResponseHandler::getResponseHandler()->sendResponse(200, ["message" => "Project created successfully"]);
            exit;
        } catch (Exception $e) {
            // Handle potential exception during database insertion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            exit;
        }
    }

     /**
     * @OA\Delete(
     *     path="/api/project/{uuid}",
     *     summary="Delete a project",
     *     operationId="deleteProject",
     *     tags={"Project"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the project to be deleted",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="204", description="Project successfully deleted."),
     *     @OA\Response(
     *         response="404",
     *         description="Project not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Project not found")
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

        try {
            $db = Database::getInstance();
            $project = $db->fetchOne("SELECT * FROM project WHERE uuid = :uuid", ['uuid' => $uuid]);

            if (!$project) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'Project not found']);
                exit;
            }

            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);
            
            if($currentUser['isAdmin']){
                $db->delete('project', ['uuid' => $uuid]);
                ResponseHandler::getResponseHandler()->sendResponse(204, ['message' => 'Project deleted successfully']);
                exit;
            }

            if($currentUser['uuid'] != $project['uuidUser']){
                ResponseHandler::getResponseHandler()->sendResponse(401, [
                    'error' => 'Unauthorized'
                ]);
                exit;
            } 


            $db->delete('project', ['uuid' => $uuid]);
            ResponseHandler::getResponseHandler()->sendResponse(204, ['message' => 'Project deleted successfully']);
            exit;
        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            exit;
        }
    }

    /**
     * @OA\Get(
     *     path="/api/user/{uuid}",
     *     summary="Retrieve project information",
     *     operationId="getProject",
     *     tags={"Project"},
     *
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the project to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Project found", @OA\JsonContent(
     *         @OA\Property(property="data", type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="chart", type="string"),
     *             @OA\Property(property="uuidUser", type="string")
     *            )
     *     )),
     *     @OA\Response(
     *         response="404",
     *         description="Project not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Project not found")
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

        $payload = $this->getPayload();
        try {
            $db = Database::getInstance();
            $project = $db->fetchOne("SELECT * FROM project WHERE uuid = :uuid", ['uuid' => $uuid]);

            if (!$project) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'Project not found']);
                exit;
            }

            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);
            
            if($currentUser['isAdmin']){
                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => [
                        'name' => $project['name'],
                        'chart' => $project['chart'],
                        'uuidUser' => $project['uuidUser']
                    ]
                ]);
                exit;
            }

            if($currentUser['uuid'] != $project['uuidUser']){
                ResponseHandler::getResponseHandler()->sendResponse(401, [
                    'error' => 'Unauthorized'
                ]);
                exit;
            } 

            ResponseHandler::getResponseHandler()->sendResponse(200, [
                'data' => [
                    'name' => $project['name'],
                    'chart' => $project['chart'],
                    'uuidUser' => $project['uuidUser']
                ]
            ]);
            exit;

        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            exit;
        }
    }

     /**
      * @return array|void
      */
     public function getPayload()
     {
         $headers = apache_request_headers();

         if (!isset($headers['Authorization'])) {
             ResponseHandler::getResponseHandler()->sendResponse(401, [
                 'error' => 'Unauthorized'
             ]);
             exit;
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
             exit;
         }
         return $payload;
     }
 }

