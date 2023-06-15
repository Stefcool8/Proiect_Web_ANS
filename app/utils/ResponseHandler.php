<?php

namespace App\utils;

class ResponseHandler {

    // singleton instance
    private static ?ResponseHandler $responseHandler = null;

    // singleton pattern
    private function __construct() {}

    /**
     * Returns the ResponseHandler instance.
     * 
     * @return ResponseHandler
     */
    public static function getResponseHandler(): ResponseHandler {
        if (self::$responseHandler == null) {
            self::$responseHandler = new ResponseHandler();
        }

        return self::$responseHandler;
    }

    /**
     * Sends a JSON response.
     *
     * @param int $statusCode HTTP status code.
     * @param array $data Data to be sent.
     *
     * @return void
     */
    public static function sendResponse(int $statusCode, array $data): void {
        $response = [
            "status_code" => $statusCode,
            "data" => $data
        ];
       
        header("Content-Type: application/json");
        http_response_code($statusCode);

        echo json_encode($response);
        exit();
    }
}
