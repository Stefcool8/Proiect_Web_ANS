<?php

namespace App\Controllers;

use App\Utils\ViewLoader;

class DashboardController {
    
    public function get(): void {
        ViewLoader::getViewLoader()->loadView('dashboard');
    }

}