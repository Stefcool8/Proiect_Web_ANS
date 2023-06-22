<?php

namespace App\Controllers;

use App\utils\ResponseHandler;
use App\utils\EmailSender;


class ContactController extends Controller {


    public function create() {
        $body = json_decode(file_get_contents('php://input'), true);

        if (empty($body['name']) || empty($body['email']) || empty($body['subject']) || empty($body['message'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body']);
            exit;
        }

        $name = $this->sanitizeData($body['name']);
        $email = $this->sanitizeData($body['email']);
        $subject = $this->sanitizeData($body['subject']);
        $message = $this->sanitizeData($body['message']);

        // check if honeypot is filled
        if (!empty($body['nickname'])) {
            ResponseHandler::getResponseHandler()->sendResponse(405, ['error' => 'Invalid request']);
        }

        // validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body']);
        }

        // build email template
        $template = file_get_contents('../public/assets/templates/contact.html');
        $template = str_replace('{name_placeholder}', $name, $template);
        $template = str_replace('{email_placeholder}', $email, $template);
        $template = str_replace('{subject_placeholder}', $subject, $template);
        $template = str_replace('{message_placeholder}', $message, $template);

        // send email to client
        $clientEmailSent = EmailSender::getEmailSender()->sendEmail($email, $name, 'Contact request submitted at ANS', $template);
        if (!$clientEmailSent) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ['error' => 'Internal server error']);
        }

        // send email to admin
        $adminEmailSent = EmailSender::getEmailSender()->sendEmail('ans.web.mail10@gmail.com', 'ANS Web', '[ANS] Contact request', $template);
        if ($adminEmailSent) {
            ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'Contact request submitted successfully']);
        } else {
            ResponseHandler::getResponseHandler()->sendResponse(500, ['error' => 'Internal server error']);
        }
    }

}
