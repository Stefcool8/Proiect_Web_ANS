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
class ProjectController extends Controller
{
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
    public function create()
    {
        // get the request body
        $body = json_decode(file_get_contents('php://input'), true);

        // get the token from the request header
        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }

        try {
            $db = Database::getInstance();
            $existingUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            if (!$existingUser) {
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "User assigned does not exist"]);
                return;
            }
            $uuidUser = $existingUser['uuid'];
        } catch (Exception $e) {
            // Handle potential exception during database connection
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
            return;
        }

        // validate the request body
        if (!isset($body['name']) || !isset($body['chart']) || !isset($body['years'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body.']);
            return;
        }

        try {
            $db = Database::getInstance();
            $existingProject = $db->fetchOne("SELECT * FROM project WHERE name = :name AND uuidUser = :uuidUser", ['name' => $body['name'], 'uuidUser' => $uuidUser]);

            // check if project exists
            if ($existingProject) {
                ResponseHandler::getResponseHandler()->sendResponse(409, ["error" => "Project already exists"]);
                return;
            }

            // check the chart type
            try {
                if ($body['chart'] == 0) {
                    $this->createChartProject($db, $body, $uuidUser, 'bar_chart', 'bars');
                } else if ($body['chart'] == 1) {
                    $this->createChartProject($db, $body, $uuidUser, 'line_chart', 'line');
                } else if ($body['chart'] == 2) {
                    $this->createChartProject($db, $body, $uuidUser, 'pie_chart', 'slices');
                } else if ($body['chart'] == 3) {
                    $this->createChartProject($db, $body, $uuidUser, 'map_chart', null);
                } else {
                    ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid chart type.']);
                    return;
                }
            } catch (Exception $e) {
                ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => $e->getMessage()]);
                return;
            }

            // send response
            ResponseHandler::getResponseHandler()->sendResponse(200, ["message" => "Project created successfully"]);
        } catch (Exception $e) {
            // Handle potential exception during database insertion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

    /**
     * @throws Exception
     */
    public function createChartProject($db, $body, $uuidUser, $tableName, $dataColumn) {
        // if the chart is not a map chart, dataColumn is required
        if ($tableName != 'map_chart' && !isset($body['dataColumn'])) {
            throw new Exception("Invalid request body.");
        }

        // create the project
        $db->insert('project', [
            'name' => $body['name'],
            'chart' => $body['chart'],
            'uuidUser' => $uuidUser,
            'uuid' => uniqid()
        ]);

        // get project uuid
        $projectUuid = $db->fetchOne("SELECT uuid FROM project WHERE name = :name AND uuidUser = :uuidUser", ['name' => $body['name'], 'uuidUser' => $uuidUser]);

        // insert in chart table
        if ($tableName == 'map_chart') {
            // if the chart is a map chart, dataColumn is omitted
            $db->insert($tableName, [
                'uuidProject' => $projectUuid['uuid']
            ]);
        } else if ($tableName == 'line_chart') {
            // if the chart is a line chart, lineValue is added
            $db->insert($tableName, [
                'uuidProject' => $projectUuid['uuid'],
                $dataColumn => $body['dataColumn'],
                'lineValue' => $body['lineValue']
            ]);
        } else {
            $db->insert($tableName, [
                'uuidProject' => $projectUuid['uuid'],
                $dataColumn => $body['dataColumn']
            ]);
        }

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
    public function delete($uuid)
    {
        $payload = $this->getPayload();

        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }

        try {
            $db = Database::getInstance();
            $project = $db->fetchOne("SELECT * FROM project WHERE uuid = :uuid", ['uuid' => $uuid]);

            if (!$project) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'Project not found']);
                return;
            }
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            if ($currentUser['isAdmin']) {
                $db->delete('project', ['uuid' => $uuid]);
                ResponseHandler::getResponseHandler()->sendResponse(204, ['message' => 'Project deleted successfully']);
                return;
            }

            if ($currentUser['uuid'] != $project['uuidUser']) {
                ResponseHandler::getResponseHandler()->sendResponse(401, [
                    'error' => 'Unauthorized'
                ]);
                return;
            }
            $db->delete('project', ['uuid' => $uuid]);

            ResponseHandler::getResponseHandler()->sendResponse(204, ['message' => 'Project deleted successfully']);
        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
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
    public function get($uuid)
    {
        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }
        try {
            $db = Database::getInstance();
            $project = $db->fetchOne("SELECT * FROM project WHERE uuid = :uuid", ['uuid' => $uuid]);

            if (!$project) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'Project not found']);
                return;
            }
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            if (!$currentUser['isAdmin']) {
                // if the user is not admin, he can only access his own projects
                if ($currentUser['uuid'] != $project['uuidUser']) {
                    ResponseHandler::getResponseHandler()->sendResponse(401, [
                        'error' => 'Unauthorized'
                    ]);
                    return;
                }
            }

            try {
                if ($project['chart'] == 0) {
                    $data = $this->getChartProject($db, $project, 'bar_chart', 'bars');
                } else if ($project['chart'] == 1) {
                    $data = $this->getChartProject($db, $project, 'line_chart', 'line');
                } else if ($project['chart'] == 2) {
                    $data = $this->getChartProject($db, $project, 'pie_chart', 'slices');
                } else if ($project['chart'] == 3) {
                    $data = $this->getChartProject($db, $project, 'map_chart', null);
                } else {
                    ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid chart type.']);
                    return;
                }

                ResponseHandler::getResponseHandler()->sendResponse(200, [
                    'data' => $data
                ]);
            } catch (Exception $e) {
                ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body.']);
            }
        } catch (Exception $e) {
            // Handle potential exception during database deletion
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

    /**
     * @throws Exception
     */
    public function getChartProject($db, $project, $tableName, $dataColumn): array {
        $data = [];

        $data['name'] = $project['name'];
        $data['chart'] = $project['chart'];

        // get the years for this project
        $years = $db->fetchAll("SELECT * FROM years WHERE uuidProject = :uuidProject", ['uuidProject' => $project['uuid']]);
        for ($i = 0; $i < count($years); $i++) {
            $data['years'][$i] = $years[$i]['year'];
        }

        // get the bars for this project
        $chartTable = $db->fetchOne("SELECT * FROM $tableName WHERE uuidProject = :uuidProject", ['uuidProject' => $project['uuid']]);
        if ($dataColumn) {
            $data['dataColumn'] = $chartTable[$dataColumn];
        } else {
            // if the chart is a map, set the dataColumn to 0 ("JUDET" column)
            $data['dataColumn'] = 0;
        }

        // check if there are optional conditions for this project
        $optional = $db->fetchOne("SELECT * FROM optional_conditions WHERE uuidProject = :uuidProject", ['uuidProject' => $project['uuid']]);

        if ($tableName == 'line_chart') {
            // if the chart is a line chart, we need to filter the data
            $data['lineValue'] = $chartTable['lineValue'];

            if ($optional) {
                $data['seriesCode'] = $optional['optionalColumn'];
                $data['seriesValue'] = $optional['optionalValue'];
                $filterColumns = [$data['seriesCode'], $data['dataColumn']];
                $filterValues = [$data['seriesValue'], $data['lineValue']];

                $json = JsonUtil::getJsonUtil()->extractTotalByYear($data['years'], $filterColumns, $filterValues, $data['dataColumn']);
            } else {
                $json = JsonUtil::getJsonUtil()->extractTotalByYear($data['years'], [$data['dataColumn']], [$data['lineValue']], $data['dataColumn']);
            }
        } else {
            if ($optional) {
                $data['seriesCode'] = $optional['optionalColumn'];
                $data['seriesValue'] = $optional['optionalValue'];
                $json = JsonUtil::getJsonUtil()->filtrateAfterYearsAndColumns($data['years'], [$data['seriesCode']], [$data['seriesValue']]);
            } else {
                $json = JsonUtil::getJsonUtil()->filtrateAfterYearsAndColumns($data['years'], [], []);
            }
            // add the json data to the response
            if ($dataColumn) {
                $json = JsonUtil::getJsonUtil()->extractTotalPerDistinctColumnValue($json, $data['dataColumn']);
            } else {
                // for map chart, dataColumn is implicitly set to 0 ("JUDET" column)
                $json = JsonUtil::getJsonUtil()->extractTotalPerDistinctColumnValue($json, 0);
            }
        }
        $data['json'] = $json;

        return $data;
    }

    public function gets($uuid)
    {
        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }
        try {
            $db = Database::getInstance();

            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            // fetch all projects for this user
            $projects = $db->fetchAll("SELECT * FROM project WHERE uuidUser = :uuidUser ORDER BY id DESC", ['uuidUser' => $uuid]);

            // if there are no projects, return a message indicating this
            if (!$projects) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'No projects found for this user']);
                return;
            }

            if ($payload['isAdmin'] || $currentUser['uuid'] == $uuid) {
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
            } else {
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
            }
        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }

    public function getByInterval($uuid, $startPage)
    {
        $payload = $this->getPayload();
        if (!$payload) {
            ResponseHandler::getResponseHandler()->sendResponse(401, [
                'error' => 'Unauthorized'
            ]);
            return;
        }
        try {
            $db = Database::getInstance();

            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            $startIndex = $startPage - 1; // The start index of the projects
            $pageSize = 4; // The number of projects to retrieve per page

            // Calculate the offset based on the start index and page size
            $offset = $startIndex * $pageSize;

            $projects = $db->fetchAll("SELECT * FROM project WHERE uuidUser = :uuidUser ORDER BY id DESC LIMIT " . $pageSize . " OFFSET " . $offset,
                ['uuidUser' => $uuid]);

            // if there are no projects, return a message indicating this
            if (!$projects) {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'No projects found for this user']);
                return;
            }

            if ($payload['isAdmin'] || $currentUser['uuid'] == $uuid) {
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
            } else {
                ResponseHandler::getResponseHandler()->sendResponse(401, [
                    'error' => 'Unauthorized'
                ]);
            }
        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ["error" => "Internal Server Error"]);
        }
    }
}
