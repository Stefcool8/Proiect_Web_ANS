<?php

namespace App\Controllers;

use App\Utils\ViewLoader;

class AboutController {

    /**
     * Get /about
     * 
     * @return void
     */
    public function get(): void {
        ViewLoader::getViewLoader()->loadView('about');
    } 

}