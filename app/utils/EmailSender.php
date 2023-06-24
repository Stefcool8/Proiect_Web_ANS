<?php

namespace App\utils;

use SendGrid;
use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;

class EmailSender {
    private static ?EmailSender $emailSender = null;

    private function __construct() {}

    /**
     * Get the EmailSender instance
     *
     * @return EmailSender
     */
    public static function getEmailSender(): EmailSender {
        if (self::$emailSender == null) {
            self::$emailSender = new EmailSender();
        }

        return self::$emailSender;
    }

    /**
     * Send an email
     *
     * @param string $receiverEmail - The email of the receiver
     * @param string $receiverName - The name of the receiver
     * @param string $subject - The subject of the email
     * @param string $content - The content of the email
     *
     * @return bool - True if the email was sent successfully, false otherwise
     */
    public function sendEmail(string $receiverEmail, string $receiverName, string $subject, string $content): bool {
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