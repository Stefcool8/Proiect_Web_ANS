<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;

class AboutController {

    /**
     * Get /about
     * Get /about/
     * 
     * @return void
     */
    public function get(): void {
        // load the view
        $view = ViewLoader::getViewLoader()->loadView('about');

        // send view response
        ResponseHandler::getResponseHandler()->sendView(200, $view, [
            "about" => [
                "about.js"
            ]
        ]);
    } 
}