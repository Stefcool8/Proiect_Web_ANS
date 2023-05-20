<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;


class AboutController {

    /**
     * @OA\Get(
     *     path="/api/about",
     *     @OA\Response(response="200", description="This method returns the data for the about page.")
     * )
     */
    public function get() {
        // send the view
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'About Us',
        ]);
    }
}