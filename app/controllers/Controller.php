<?php
// DONE
namespace App\Controllers;

use App\Utils\ResponseHandler;
use App\Utils\Database;
use App\Utils\JWT;
use Exception;
use InvalidArgumentException;


class Controller {
    /**
     * @return array|void
     */
    protected function getPayload()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return null;
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // decode the token
            $payload = JWT::getJWT()->decode($token);
        } catch (\InvalidArgumentException $e) {
            return null;
        }
        return $payload;
    }

}