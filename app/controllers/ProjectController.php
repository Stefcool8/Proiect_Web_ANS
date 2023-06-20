<?php
// DONE
namespace App\controllers;

use App\Utils\ResponseHandler;
use App\Utils\Database;
use App\Utils\JsonUtil;
use Exception;


/** 
 * Controller for Project operations
 * 
 */

 class ProjectController extends Controller{

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

        // get the token from the request header
        $payload = $this->getPayload();
        if(!$payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }

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
        if (!isset($body['name']) || !isset($body['chart']) || !isset($body['years'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body.']);
            exit;
        }

        try {
            $db = Database::getInstance();

            $existingProject = $db->fetchOne("SELECT * FROM project WHERE name = :name AND uuidUser = :uuidUser", ['name' => $body['name'],'uuidUser' =>$uuidUser]);

            // check if project exists
            if ($existingProject) {
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "Project already exists"]);
                exit;
            }

            // check the chart type
            if ($body['chart'] == 0) {
                $this->createBarChartProject($db, $body, $uuidUser);
            } else {
                $this->createProject($db, $body, $uuidUser);
            }

            // send the data
            ResponseHandler::getResponseHandler()->sendResponse(200, ["message" => "Project created successfully"]);
            exit;
        } catch (Exception $e) {
            // Handle potential exception during database insertion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            exit;
        }
    }

    public function createBarChartProject($db, $body, $uuidUser) {
        // if the chart is a bar chart, check the bars
        if (!isset($body['bars'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body.']);
            exit;
        }

        // create the project
        $this->createProject($db, $body, $uuidUser);

        // get project uuid
        $projectUuid = $db->fetchOne("SELECT uuid FROM project WHERE name = :name AND uuidUser = :uuidUser", ['name' => $body['name'],'uuidUser' =>$uuidUser]);

        // insert in bar_chart table
        $db->insert('bar_chart', [
            'uuidProject' => $projectUuid['uuid'],
            'bars' => $body['bars']
        ]);

        // insert in years table
        foreach ($body['years'] as $year) {
            $db->insert('years', [
                'uuidProject' => $projectUuid['uuid'],
                'year' => $year
            ]);
        }

        // insert in optional_conditions table if series is set
        if (isset($body['seriesCode'])) {
            $db->insert('optional_conditions', [
                'uuidProject' => $projectUuid['uuid'],
                'optionalColumn' => $body['seriesCode'],
                'optionalValue' => $body['seriesValue']
            ]);
        }
    }

    public function createProject($db, $body, $uuidUser) {
        $db->insert('project', [
            'name' => $body['name'],
            'chart' => $body['chart'],
            'uuidUser' => $uuidUser,
            'uuid' => uniqid()
        ]);
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

        if(!$payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }

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
     *     path="/api/project/{uuid}",
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
        if(!$payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            exit;
        }
        try {
            $db = Database::getInstance();
            $project = $db->fetchOne("SELECT * FROM project WHERE uuid = :uuid", ['uuid' => $uuid]);

            if (!$project) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'Project not found']);
                exit;
            }

            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);
            
            /*if($currentUser['isAdmin']){
                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => [
                        'name' => $project['name'],
                        'chart' => $project['chart'],
                        'uuidUser' => $project['uuidUser']
                    ]
                ]);
                exit;
            }*/

            if($currentUser['uuid'] != $project['uuidUser']){
                ResponseHandler::getResponseHandler()->sendResponse(401, [
                    'error' => 'Unauthorized'
                ]);
                exit;
            }

            if ($project['chart'] == 0) {
                // send the project data for the bar chart
                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => $this->getBarChartProject($db, $project)
                ]);
            } else {
                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => [
                        'name' => $project['name'],
                        'chart' => $project['chart'],
                        'uuidUser' => $project['uuidUser']
                    ]
                ]);
            }

        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            exit;
        }
    }

     public function gets($uuid) {
         $payload = $this->getPayload();
         if(!$payload){
             ResponseHandler::getResponseHandler()->sendResponse(401, [
                 'error' => 'Unauthorized'
             ]);
             exit;
         }
         try {
             $db = Database::getInstance();

             $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

             // fetch all projects for this user
             $projects = $db->fetchAll("SELECT * FROM project WHERE uuidUser = :uuidUser", ['uuidUser' => $uuid]);

             // if there are no projects, return a message indicating this
             if (!$projects) {
                 ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'No projects found for this user']);
                 exit;
             }

             if($payload['isAdmin'] || $currentUser['uuid'] == $uuid) {
                 // build the project data for the response
                 $projectData = [];
                 foreach ($projects as $project) {
                     $projectData[] = [
                         'name' => $project['name'],
                         'chart' => $project['chart'],
                         'uuid' => $project['uuid']
                     ];
                 }

                 ResponseHandler::getResponseHandler()->sendResponse(200, ['projects' => $projectData]);
             }
             else{
                 ResponseHandler::getResponseHandler()->sendResponse(401, [
                     'error' => 'Unauthorized'
                 ]);
             }

         } catch (Exception $e) {
             ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
         }
     }

     public function getByInterval($uuid,$startPage){
         $payload = $this->getPayload();
         if(!$payload){
             ResponseHandler::getResponseHandler()->sendResponse(401, [
                 'error' => 'Unauthorized'
             ]);
             exit;
         }
         try {
             $db = Database::getInstance();

             $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

             $startIndex = $startPage-1; // The start index of the projects
             $pageSize = 4; // The number of projects to retrieve per page

             // Calculate the offset based on the start index and page size
             $offset = $startIndex * $pageSize;
             // fetch all projects for this user
             //$projects = $db->fetchAll("SELECT * FROM project WHERE uuidUser = :uuidUser", ['uuidUser' => $uuid]);

             // $projects = $db->fetchAll("SELECT * FROM project WHERE uuidUser = :uuidUser LIMIT ".$pageSize,
             //  ['uuidUser' => $uuid]);

             $projects = $db->fetchAll("SELECT * FROM project WHERE uuidUser = :uuidUser LIMIT " . $pageSize . " OFFSET ".$offset,
                 ['uuidUser' => $uuid]);

             // if there are no projects, return a message indicating this
             if (!$projects) {
                 ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'No projects found for this user']);
                 exit;
             }

             if($payload['isAdmin'] || $currentUser['uuid'] == $uuid) {

                 // build the project data for the response
                 $projectData = [];
                 foreach ($projects as $project) {
                     $projectData[] = [
                         'name' => $project['name'],
                         'chart' => $project['chart'],
                         'uuid' => $project['uuid']
                     ];
                 }

                 ResponseHandler::getResponseHandler()->sendResponse(200, ['projects' => $projectData]);
             }
             else{
                 ResponseHandler::getResponseHandler()->sendResponse(401, [
                     'error' => 'Unauthorized'
                 ]);
                 exit;
             }
         } catch (Exception $e) {
             ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
         }
     }
    public function getBarChartProject($db, $project): array {
        $data = [];

        $data['name'] = $project['name'];
        $data['chart'] = $project['chart'];

        // get the years for this project
        $years = $db->fetchAll("SELECT * FROM years WHERE uuidProject = :uuidProject", ['uuidProject' => $project['uuid']]);
        for ($i = 0; $i < count($years); $i++) {
            $data['years'][$i] = $years[$i]['year'];
        }

        // get the bars for this project
        $bars = $db->fetchOne("SELECT * FROM bar_chart WHERE uuidProject = :uuidProject", ['uuidProject' => $project['uuid']]);
        $data['bars'] = $bars['bars'];

        // check if there are optional conditions for this project
        $optional = $db->fetchOne("SELECT * FROM optional_conditions WHERE uuidProject = :uuidProject", ['uuidProject' => $project['uuid']]);

        // if there are optional conditions, add them to the response
        // also send the json data
        if ($optional) {
            $data['seriesCode'] = $optional['optionalColumn'];
            $data['seriesValue'] = $optional['optionalValue'];

            $json = JsonUtil::getJsonUtil()->filtrateAfterYearsAndColumns($data['years'], [$data['seriesCode']], [$data['seriesValue']]);
        } else {
            $json = JsonUtil::getJsonUtil()->filtrateAfterYearsAndColumns($data['years'], [], []);
        }
        // add the json data to the response
        $json = JsonUtil::getJsonUtil()->extractTotalPerDistinctColumnValue($json, $data['bars']);
        $data['json'] = $json;

        return $data;
    }

 }

