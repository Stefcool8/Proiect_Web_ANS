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
        echo "$toEmail, $subject, $body, $headers";
        try {
            $this->send($toEmail, $subject, $body, $headers);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
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
     * Prepare the email body.
     * 
     * @param string $name The name
     * @param string $email The email address
     * @param string $subject The subject
     * @param string $message The message
     * 
     * @return string The email body
     */
    private function prepareBody(string $name, string $email, string $subject, string $message): string {
        return '
            <h2>Contact Request</h2>
            <h4>Name:</h4> <p>' . $name . '</p>
            <h4>Email address:</h4> <p>' . $email . '</p>
            <h4>Subject:</h4> <p>' . $subject . '</p>
            <h4>Message:</h4> <p>' . $message . '</p>
        ';
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
            $error = error_get_last();
            if ($error !== null) {
                error_log('Mailer Error: ' . $error['message']);  // Log the error
                throw new \Exception('Mailer Error: ' . $error['message']);
            } else {
                throw new \Exception('Mailer Error: Failed to send email');
            }
        }
    }


}
