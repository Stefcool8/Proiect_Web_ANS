<?php
// DONE
namespace App\controllers;

use App\Utils\ResponseHandler;

/**
 * Controller for the home page.
 * 
 * @OA\Info(title="AutoParkExplorer API", version="1.0")
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     in="header",
 *     name="Authorization",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */
class HomeController {

    /**
     * @OA\Get(
     *     path="/api/home",
     *     summary="Retrieve homepage data",
     *     operationId="getHome",
     *     tags={"Home"},
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
     *                     example="Open source tool for data visualization"
     *                 )
     *             )
     *         )
     *     ),
     * )
     */
    public function get() {
        // send the view
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Open source tool for data visualization',
        ]);
    }
}
