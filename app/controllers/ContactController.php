<?php

namespace App\Controllers;

use App\Utils\Mailer;
use App\Utils\ViewLoader;
use App\Utils\ResponseHandler;

/**
 * Contact controller
 * 
 * @package App\Controllers
 */
class ContactController {

    /**
     * Post /contact
     * Post /contact/
     * 
     * @return void
     */
    public function post(): void {
        // TODO: Validate the inputs
    }


    /**
     * Get /contact
     * Get /contact/
     * 
     * @return void
     */
    public function get(): void {
        // load the view
        $view = ViewLoader::getViewLoader()->loadView('contact');

        // send view response
        ResponseHandler::getResponseHandler()->sendView(200, $view, [
            "contact" => [
                "contact.js"
            ]
        ]);
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

}
