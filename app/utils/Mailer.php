<?php

namespace App\Utils;

class Mailer {
    public function sendEmail(string $toEmail, string $fromName, string $fromEmail, string $subject, string $body): bool {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $fromName . "<" . $fromEmail . ">" . "\r\n";

        try {
            $this->send($toEmail, $subject, $body, $headers);
            return TRUE;
        } catch (\Exception $e) {
            return FALSE;
        }
    }

    private function send(string $toEmail, string $subject, string $body, string $headers): void {
        mail($toEmail, $subject, $body, $headers);
    }
}