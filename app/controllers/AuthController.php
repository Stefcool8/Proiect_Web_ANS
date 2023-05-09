<?php

namespace App\Controllers;

use App\Utils\ViewLoader;

class AuthController {

    /**
     * Get /auth/login
     * 
     * @return void
     */
    public function getLogin(): void {
        ViewLoader::getViewLoader()->loadView('login');
    }
}