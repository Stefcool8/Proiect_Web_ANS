<?php

namespace App\Utils;

class ResponseHandler {

    // singleton instance
    private static $responseHandler;

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
     * Sends a View response.
     * 
     * @param int $statusCode HTTP status code.
     * @param string $content Content of the response.
     * @param array $scripts Scripts to be loaded.
     * 
     * @return void
     */
    public static function sendView(int $statusCode, string $content, array $scripts = []): void {
        $response = [
            "status_code" => $statusCode,
            "content" => $content,
            "scripts" => $scripts
        ];

        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo json_encode($response);
        exit();
    }

    /**
     * Sends a JSON response.
     * 
     * @param int $statusCode HTTP status code.
     * @param string $data Data to be sent.
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
