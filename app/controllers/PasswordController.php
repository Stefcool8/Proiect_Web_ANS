<?php

namespace App\Controllers;

use App\Utils\Database;
use App\Utils\JWT;
use App\Utils\ResponseHandler;
use App\Utils\EmailSender;

use Exception;

/**
 * Controller for password operations
 *
 */
class PasswordController {

    /**
     * @OA\Post(
     *     path="/api/password/reset",
     *     summary="Request a password reset",
     *     tags={"Password"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Email address of user",
     *         @OA\JsonContent(
     *            @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset link sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset link sent successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    public function forgotPassword() {
        $body = json_decode(file_get_contents('php://input'), true);
        $email = $body['email'];

        $db = Database::getInstance();
        $user = $db->fetchOne('SELECT * FROM user WHERE email = :email', ['email' => $email]);

        if ($user) {
            $token = JWT::encode(['email' => $email, 'exp' => time() + 3600]);  // Expires in 1 hour
            $url = 'http://localhost/password/reset?token=' . $token;

            $template = file_get_contents('../public/assets/templates/password-reset.html');
            $template = str_replace('{url_placeholder}', $url, $template);

            $clientEmailSent = EmailSender::sendEmail($email, $user['username'], 'Password Reset', $template);

            if ($clientEmailSent) {
                ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'Password reset link sent successfully']);
            } else {
                exit;
            }

        } else {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'User not found']);
        }
    }

    public function resetPassword() {
        $body = json_decode(file_get_contents('php://input'), true);
        $token = $body['token'];
        $password = $body['password'];

        try {
            $decoded = JWT::getJWT()->decode($token);

            $db = Database::getInstance();
            $user = $db->fetchOne('SELECT * FROM user WHERE email = :email', ['email' => $decoded['email']]);

            if ($user) {
                $db->update('user', ['password' => password_hash($password, PASSWORD_DEFAULT)], ['id' => $user['id']]);
                ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'Password reset successfully']);
            } else {
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'User not found']);
            }
        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Invalid token: ' . $e->getMessage()]);
        }
    }

}
