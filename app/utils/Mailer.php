<?php

namespace App\Utils;

/**
 * Class Mailer
 *
 * This class is responsible for sending emails.
 *
 * @package App\Utils
 */
class Mailer {

    // singleton instance
    private static ?Mailer $mailer = null;

    // private constructor to prevent direct creation of object
    private function __construct() {
    }   

    /**
     * Get the Mailer instance.
     *
     * @return Mailer The Mailer instance
     */
    public static function getMailer(): Mailer {
        if (self::$mailer === null) {
            self::$mailer = new Mailer();
        }

        return self::$mailer;
    }

    /**
     * Send an email.
     *
     * @param string $toEmail The recipient's email address
     * @param string $fromName The sender's name
     * @param string $fromEmail The sender's email address
     * @param string $subject The email subject
     * @param string $body The email body
     *
     * @return bool Returns TRUE if the email was sent successfully, FALSE otherwise
     */
    public function sendEmail(string $toEmail, string $fromName, string $fromEmail, string $subject, string $body): bool {
        $headers = $this->prepareHeaders($fromName, $fromEmail);

        try {
            $this->send($toEmail, $subject, $body, $headers);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Prepare the email headers.
     *
     * @param string $fromName The sender's name
     * @param string $fromEmail The sender's email address
     *
     * @return string The prepared headers
     */
    private function prepareHeaders(string $fromName, string $fromEmail): string {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $fromName . "<" . $fromEmail . ">" . "\r\n";

        return $headers;
    }

    /**
     * Send the email.
     *
     * @param string $toEmail The recipient's email address
     * @param string $subject The email subject
     * @param string $body The email body
     * @param string $headers The email headers
     *
     * @throws \Exception If the email fails to send
     *
     * @return void
     */
    private function send(string $toEmail, string $subject, string $body, string $headers): void {
        if (!mail($toEmail, $subject, $body, $headers)) {
            throw new \Exception("Failed to send email.");
        }
    }
}
