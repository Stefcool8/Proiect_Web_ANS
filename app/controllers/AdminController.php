<?php

namespace App\controllers;

use App\utils\ResponseHandler;

/**
 * Controller for the Admin page.
 *
 */
class AdminController
{

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
     */
    public function getInfo() {
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Admin Page',
        ]);
    }

}