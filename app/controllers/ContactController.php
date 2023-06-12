<?php
// DONE
namespace App\controllers;

use App\Utils\ResponseHandler;

/**
 * Controller for the Contact page.
 *
 */
class ContactController {

    /**
     * @OA\Get(
     *     path="/api/contact",
     *     summary="Retrieve the contact us page data",
     *     operationId="getContact",
     *     tags={"Contact"},
     *     @OA\Response(
     *         response=200,
     *         description="This method returns the data for the contact page.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Contact Us")
     *             )
     *         )
     *     )
     * )
     */
    public function get() {
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Contact Us',
        ]);
    }
}
