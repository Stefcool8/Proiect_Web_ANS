<?php

namespace App\controllers;

use App\utils\ResponseHandler;

/**
 * Controller for the about us page.
 * 
 */
class AboutController {

    /**
     * @OA\Get(
     *     path="/api/about",
     *     summary="Retrieve about us page data",
     *     operationId="getAbout",
     *     tags={"About"},
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
     *                     example="About Us"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function get() {
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'About Us',
        ]);
    }
}
