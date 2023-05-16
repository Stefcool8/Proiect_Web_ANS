<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;

/**
 * @OA\Info(title="Contact API", version="1.0")
 * 
 */
class ContactController {

    /**
     * @OA\Get(
     *     path="/api/contact",
     *     @OA\Response(response="200", description="This method returns the data for the contact page.")
     * )
     */
    public function get() {
        // send the view
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Contact Us',
        ]);
    }
}
