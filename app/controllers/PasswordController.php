<?php

namespace App\controllers;

use App\utils\Database;
use App\utils\JWT;
use App\utils\ResponseHandler;
use App\utils\EmailSender;

use Exception;

/**
 * Controller for password operations
 *
 */
class PasswordController{

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
            $token = JWT::encode(['email' => $email, 'exp' => time() + 3600]);
            $url = 'http://localhost/password/reset?token=' . $token;

            $template = file_get_contents('../public/assets/templates/password-reset.html');
            $template = str_replace('{url_placeholder}', $url, $template);

            $clientEmailSent = EmailSender::getEmailSender()->sendEmail($email, $user['username'], 'Password Reset', $template);

            if ($clientEmailSent) {
                ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'Password reset link sent successfully']);
            } else {
                exit;
            }

        } else {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'User not found']);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/password/reset",
     *     tags={"Password"},
     *     summary="Reset user password",
     *     operationId="resetPassword",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User password reset",
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="The new password"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not found, Unauthorized or Invalid token",
     *     )
     * )
     */
    public function resetPassword() {
        $body = json_decode(file_get_contents('php://input'), true);
        $password = $body['password'];

        // get the token from the request header
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
        }

        try {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
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
