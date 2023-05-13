<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;

/**
 * Home controller
 *
 * @package App\Controllers
 */
class HomeController {

    /**
     * Get /home
     * Get /
     * Get /home/
     * 
     * @return void
     */
    public function get() {
        // load the view
        $view = ViewLoader::getViewLoader()->loadView('home');

        // send the view
        ResponseHandler::getResponseHandler()->sendView(200, $view, [
            "home" => [
                "slider.js",
                "youtube.js"
            ]
        ]);
    }
}
