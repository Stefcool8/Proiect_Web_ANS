<?php

namespace App\controllers;

use App\Utils\JWT;
use InvalidArgumentException;

class Controller {
    /**
     * Method to sanitize data
     *
     * @param string $data
     * @return string
     */
    function sanitizeData(string $data): string {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    /**
     * Method to get the payload from the request
     *
     * @return array
     */
    protected function getPayload(): ?array {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return null;
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // decode the token
            $payload = JWT::getJWT()->decode($token);
        } catch (InvalidArgumentException $e) {
            return null;
        }
        return $payload;
    }
}