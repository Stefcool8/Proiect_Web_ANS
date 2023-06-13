<?php

namespace App\Utils;

use SendGrid;
use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;

class EmailSender {
    public static function sendEmail(string $receiverEmail, string $receiverName, string $subject, string $content): bool {
        try {
            $mail = new Mail();
            $mail->setFrom('ans.web.mail10@gmail.com', 'ANS Web');
            $mail->setSubject($subject);
            $mail->addTo($receiverEmail, $receiverName);
            $mail->addContent(
                "text/html",
                $content
            );
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            $response = $sendgrid->send($mail);

            if ($response->statusCode() !== 202) {
                ResponseHandler::getResponseHandler()->sendResponse(500, ['error' => 'Internal Server Error' . $response->body()]);
                return false;
            }
            else {
                return true;
            }
        } catch (TypeException $e) {
            ResponseHandler::getResponseHandler()->sendResponse(500, ['error' => 'Internal Server Error' . $e->getMessage()]);
            return false;
        }
    }
}