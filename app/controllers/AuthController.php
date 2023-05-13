<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\JWT;
use App\Repositories\UserRepository;
use App\Utils\ResponseHandler;

class AuthController {

    /**
     * Get /auth/login
     * Get /auth/login/
     * 
     * @return void
     */
    public function getLogin() {
        // load the view
        $view = ViewLoader::getViewLoader()->loadView('login');

        // sends view response
        ResponseHandler::getResponseHandler()->sendView(200, $view, [
            "auth/login" => [
                "login.js"
            ]
        ]);
    }

    /**
     * Post /auth/login
     * Post /auth/login/
     * 
     * @return void
     */
    public function postLogin(): void {
        $userRepository = new UserRepository();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {            
            ResponseHandler::getResponseHandler()->sendResponse(405, 'Method not allowed'); // Method not allowed
            return;
        }

        if (empty($_POST['username']) || empty($_POST['password'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, 'Bad request'); // Bad request
            return;
        }

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $user = $userRepository->getByUsername($username);

        if ($user && password_verify($password, $user->getPassword())) {
            
            $payload = [
                'sub' => $user->getId(),
                'username' => $user->getUsername(),
            ];
            $token = JWT::getJWT()->encode($payload);
            
            ResponseHandler::getResponseHandler()->sendResponse(200, $token); // OK

        } else {
            ResponseHandler::getResponseHandler()->sendResponse(401, 'Unauthorized'); // Unauthorized
        }
    }

    /**
     * Get /auth/register
     * 
     * @return void
     */
    public function getRegister(): void {
        // load the view
        $view = ViewLoader::getViewLoader()->loadView('register');

        // sends view response
        ResponseHandler::getResponseHandler()->sendView(200, $view, [
            "auth/register" => [
                "register.js"
            ]
        ]);
    }

    /**
     * Sends a JSON response.
     * 
     * @param int $statusCode HTTP status code.
     * @param string $content Content of the response.
     * @param array $scripts Scripts to be loaded.
     * 
     * @return void
     */
    private function sendJsonResponse(int $statusCode, string $content, array $scripts): void {
        $response = [
            "status_code" => $statusCode,
            "content" => $content,
            "scripts" => $scripts
        ];

        header('Content-Type: application/json'); // Set the content type to JSON
        http_response_code($statusCode); // Set the response code

        echo json_encode($response);
        exit();
    }

}