<?php

namespace App\Controllers;

use App\Utils\Database;
use App\Utils\JWT;
use App\Utils\ResponseHandler;

/**
 * Controller for password operations.
 *
 */
class PasswordController {

    /**
     * @OA\Post(
     *     path="/api/password/reset",
     *     summary="Send password reset link to user",
     *     operationId="forgotPassword",
     *     tags={"Password"},
     *     @OA\RequestBody(
     *          description="User email",
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="email", type="string", example="user@gmail.com")
     *    )
     * ),
     *     @OA\Response(
     *          response=200,
     *     description="Password reset link sent successfully",
     *     @OA\JsonContent(
     *     @OA\Property(property="status_code", type="integer", example=200),
     *     @OA\Property(property="message", type="string", example="Password reset link sent successfully")
     *    )
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Invalid request body",
     *     @OA\JsonContent(
     *     @OA\Property(property="status_code", type="integer", example=400),
     *     @OA\Property(property="error", type="string", example="Invalid request body")
     *   )
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="User not found",
     *     @OA\JsonContent(
     *     @OA\Property(property="status_code", type="integer", example=404),
     *     @OA\Property(property="error", type="string", example="User not found")
     *  )
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *     @OA\Property(property="status_code", type="integer", example=500),
     *     @OA\Property(property="error", type="string", example="Internal Server Error")
     * )
     * )
     */
    public function forgotPassword()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $email = $body['email'];

        $db = Database::getInstance();
        $user = $db->fetchOne('SELECT * FROM user WHERE email = :email', ['email' => $email]);

        if ($user) {
            $token = JWT::encode(['email' => $email, 'exp' => time() + 3600]);  // Expires in 1 hour

            $url = 'http://localhost:8080/password/reset?token=' . $token;

            // Send email with reset link
            $to = $email;
            $subject = 'Password reset';
            $message = "Click on the link below to reset your password:\n\n$url";
            $headers = 'From: ans.web.mail10@gmail.com' . "\r\n" .
                'Reply-To:' . $email . "\r\n" . 'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);

            ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'Password reset link sent to your email']);
        } else {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'User not found']);
        }
    }
}
