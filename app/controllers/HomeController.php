<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;

/**
 * @OA\Info(title="AutoParkExplorer API", version="1.0")
 * 
 */
class HomeController {

    /**
     * @OA\Get(
     *     path="/api/home",
     *     @OA\Response(response="200", description="This method returns the data for the home page.")
     * )
     */
    public function get() {
        // send the view
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Open source tool for data visualization',
        ]);
    }
}

// mysecretpassword!2002
// Cabo0Y2FsHbRDVQbYWf6j2myOhk3E4Na