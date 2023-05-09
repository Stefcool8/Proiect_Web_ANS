<?php

namespace App\Controllers;

use App\Utils\Mailer;
use App\Utils\ViewLoader;

class ContactController {

    /**
     * Post /contact
     * 
     * @return void
     */
    public function post(): void {
        $mailer = Mailer::getMailer();
        $statusCode = 400; // Bad Request
        $msg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

            if ($this->validateInputs($name, $email, $subject, $message)) {
                $toEmail = 'autoparkexplorer@gmail.com';
                $fromName = $name;
                $fromEmail = $email;
                $body = $this->prepareBody($name, $email, $subject, $message);

                if ($mailer->sendEmail($toEmail, $fromName, $fromEmail, $subject, $body)) {
                    $statusCode = 200; // OK
                    $msg = 'Message has been sent';
                } else {
                    $statusCode = 500; // Internal Server Error
                    $msg = 'Message could not be sent';
                }
            } else {
                $statusCode = 400; // Bad Request
                $msg = 'Fill in all the fields';
            }
        }

        $this->sendResponse($statusCode, $msg);
    }

    /**
     * Get /contact
     * 
     * @return void
     */
    public function get(): void {
        ViewLoader::getViewLoader()->loadView('contact');
    } 

    /**
     * Validate the inputs.
     * 
     * @param string $name The name
     * @param string $email The email address
     * @param string $subject The subject
     * @param string $message The message
     * 
     * @return bool Returns TRUE if the inputs are valid, FALSE otherwise
     */
    private function validateInputs(string $name, string $email, string $subject, string $message): bool {
        return !(empty($email) || empty($name) || empty($subject) || empty($message));
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
     * Send the response.
     * 
     * @param int $statusCode The status code
     * @param string $msg The message
     * 
     * @return void
     */
    private function sendResponse(int $statusCode, string $msg): void {
        $response = [
            'status' => $statusCode,
            'message' => $msg
        ];

        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($response);
    }
}
