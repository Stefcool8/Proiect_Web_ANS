<?php

namespace App\Controllers;

use App\Utils\Mailer;

class ContactController {

    public function postContact() {
        $mailer = new Mailer();
        $statusCode = 400; // Bad Request
        $msg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

            if (!empty($email) && !empty($name) && !empty($subject) && !empty($message)) {
                
                $toEmail = 'autoparkexplorer@gmail.com';
                $fromName = $name;
                $fromEmail = $email;
                $body = '
                        <h2>Contact Request</h2>
                        <h4>Name:</h4> <p>' . $name . '</p>
                        <h4>Email address:</h4> <p>' . $email . '</p>
                        <h4>Subject:</h4> <p>' . $subject . '</p>
                        <h4>Message:</h4> <p>' . $message . '</p>
                        ';

                if ($mailer->sendEmail($toEmail, $fromName, $fromEmail, $subject, $body)) {
                    $statusCode = 200; // OK
                    $msg = 'Message has been sent';
                } else {
                    $statusCode = 500; // Internal Server Error
                    $msg = 'Message could not be sent';
                }
            } else {
                $statusCode = 400; // Bad Request
                $msg = 'Please fill in all fields';
            }
        }

        $response = [
            'status' => $statusCode,
            'message' => $msg
        ];

        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($response);
    }
}
