<?php

namespace App\Controllers;

use App\Utils\ViewLoader;

/**
 * Home controller
 *
 * @package App\Controllers
 */
class HomeController {

    /**
     * Get /home
     * Get /
     * 
     * @return void
     */
    public function get() {
        ViewLoader::getViewLoader()->loadView('home');
    }

}