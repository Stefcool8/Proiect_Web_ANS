<?php

namespace App\Controllers;

use App\Utils\ViewLoader;
use App\Utils\JWT;
use App\Repositories\UserRepository;

class AuthController {

    /**
     * Get /auth/login
     * 
     * @return void
     */
    public function getLogin(): void {
        ViewLoader::getViewLoader()->loadView('login');
    }

    /**
     * Post /auth/login
     * 
     * @return void
     */
    public function postLogin(): void {
        $userRepository = new UserRepository();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {            
            $this->sendResponse(405, 'Method Not Allowed'); // Method Not Allowed
            return;
        }

        if (empty($_POST['username']) || empty($_POST['password'])) {
            $this->sendResponse(400, 'Fill in all the fields'); // Bad Request
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
            echo json_encode([
                'token' => $token,
            ]);

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['username'] = $user->getUsername();

        } else {
            $this->sendResponse(401, 'Login failed'); // Unauthorized
        }
    }


    private function sendResponse(int $statusCode, string $message): void {
        http_response_code($statusCode);
        echo json_encode([
            'status_code' => $statusCode,
            'message' => $message
        ]);
    }

    /**
     * Get /auth/register
     * 
     * @return void
     */
    public function getRegister(): void {
        ViewLoader::getViewLoader()->loadView('register');
    }

}