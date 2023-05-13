<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\JWT;
use App\Utils\ResponseHandler;

class DashboardController {
    
    public function get(): void {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            ResponseHandler::getResponseHandler()->sendResponse(402, 'Unauthorized'); // Unauthorized
            return;
        }

        $authHeader = $headers['Authorization'];
        $jwt = str_replace('Bearer ', '', $authHeader);

        if (!JWT::getJWT()->decode($jwt)) {
            ResponseHandler::getResponseHandler()->sendResponse(403, 'Unauthorized'); // Unauthorized
            return;
        }

        $payload = JWT::getJWT()->decode($jwt);
        $userId = $payload['sub'];
        $username = $payload['username'];

        $view = ViewLoader::getViewLoader()->loadView('dashboard', [
            "username" => $username
        ]);

        ResponseHandler::getResponseHandler()->sendView(200, $view, [
            "dashboard" => [
                "dashboard.js"
            ],
            "navbar" => [
                "navbar.js"
            ],
        ]);
    }
}