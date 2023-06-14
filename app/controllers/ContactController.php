<?php
// DONE
namespace App\Controllers;

use App\Utils\ResponseHandler;
use App\Utils\EmailSender;

/**
 * Controller for the Contact page.
 *
 */
class ContactController {

    public function create() {
        $body = json_decode(file_get_contents('php://input'), true);
        $name = $body['name'];
        $email = $body['email'];
        $subject = $body['subject'];
        $message = $body['message'];

        // Check if honeypot is filled
        if (!empty($body['nickname'])) {
            ResponseHandler::getResponseHandler()->sendResponse(405, ['error' => 'Invalid request']);
            exit;
        }

        // validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body']);
            exit;
        }

        // build email template
        $template = file_get_contents('../public/assets/templates/contact.html');
        $template = str_replace('{name_placeholder}', $name, $template);
        $template = str_replace('{email_placeholder}', $email, $template);
        $template = str_replace('{subject_placeholder}', $subject, $template);
        $template = str_replace('{message_placeholder}', $message, $template);

        // send email to client
        $clientEmailSent = EmailSender::sendEmail($email, $name, 'Contact request submitted at ANS', $template);
        if (!$clientEmailSent) {
            exit;
        }

        // send email to admin
        $adminEmailSent = EmailSender::sendEmail('ans.web.mail10@gmail.com', 'ANS Web', '[ANS] Contact request', $template);
        if ($adminEmailSent) {
            ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'Contact request submitted successfully']);
        }
        else {
            exit;
        }
    }
}
