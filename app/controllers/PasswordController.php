<?php

namespace App\controllers;

use App\utils\Database;
use App\utils\JWT;
use App\utils\ResponseHandler;
use App\utils\EmailSender;

use Exception;


class PasswordController extends Controller {


    public function forgotPassword() {
        $body = json_decode(file_get_contents('php://input'), true);
        $email = $this->sanitizeData($body['email']);

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


    public function resetPassword() {
        $body = json_decode(file_get_contents('php://input'), true);
        $password = $this->sanitizeData($body['password']);

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


    public function changePassword($uuid) {
        $body = json_decode(file_get_contents('php://input'), true);

        if (!isset($body['currentPassword']) || !isset($body['newPassword'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Missing required fields']);
            exit;
        }

        $currentPassword = $this->sanitizeData($body['currentPassword']);
        $newPassword = $this->sanitizeData($body['newPassword']);

        // get the token from the request header
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
            exit;
        }

        try {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            $decoded = JWT::getJWT()->decode($token);

            $db = Database::getInstance();
            $user = $db->fetchOne('SELECT * FROM user WHERE uuid = :uuid', ['uuid' => $uuid]);

            // check if is admin or the user is changing his own password
            if (!$decoded['isAdmin'] && $decoded['username'] !== $user['username']) {
                ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Unauthorized']);
                exit;
            }

            if ($user) {
                // check if the provided current password matches the existing one
                if (password_verify($currentPassword, $user['password'])) {
                    $db->update('user', ['password' => password_hash($newPassword, PASSWORD_DEFAULT)], ['uuid' => $uuid]);
                    ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'Password updated successfully']);
                } else {
                    ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Incorrect current password']);
                }
            } else {
                ResponseHandler::getResponseHandler()->sendResponse(404, ['error' => 'User not found']);
            }
        } catch (Exception $e) {
            ResponseHandler::getResponseHandler()->sendResponse(401, ['error' => 'Invalid token: ' . $e->getMessage()]);
        }
    }
}
