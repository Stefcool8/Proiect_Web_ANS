<?php
namespace App\controllers;

use App\utils\ResponseHandler;
use InvalidArgumentException;
use App\Utils\Database;

class DashboardController extends Controller {


    public function get() {
        // get the token from the request header
        $payload = $this->getPayload();

        if(!$payload){
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
            return;
        }
        try {
            $db = Database::getInstance();
            $currentUser = $db->fetchOne("SELECT * FROM user WHERE username = :username", ['username' => $payload['username']]);

            if (!$currentUser) {
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
                return;
            }

            ResponseHandler::getResponseHandler()->sendResponse(200, [
                'data' => [
                    'title' => 'Dashboard',
                    'username' => $payload['username'],
                    'isAdmin' => $currentUser['isAdmin'],
                    'uuid' => $currentUser['uuid']
                ]
            ]);
        } catch (InvalidArgumentException $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
        }
    }
}
