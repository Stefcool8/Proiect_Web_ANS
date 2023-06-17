<?php

namespace App\Controllers;

use App\utils\ResponseHandler;
use App\utils\EmailSender;

/**
 * Controller for the Contact page
 *
 */
class ContactController {

    /**
     * @OA\Post(
     *     path="/api/contact",
     *     summary="Submit a contact request",
     *     tags={"Contact"},
     *     @OA\RequestBody(
     *         description="Contact form data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "email", "subject", "message", "nickname"},
     *                 @OA\Property(
     *                     property="name",
     *                     description="The name of the user",
     *                     type="string",
     *                     example="John Doe"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="The email of the user",
     *                     type="string",
     *                     example="martinescunicolaee3@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="subject",
     *                     description="The subject of the contact request",
     *                     type="string",
     *                     example="I have a question"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     description="The message of the contact request",
     *                     type="string",
     *                     example="I have a question about the website"
     *                 ),
     *                 @OA\Property(
     *                     property="nickname",
     *                     description="Honeypot field",
     *                     type="string",
     *                     example=""
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact request submitted successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="message",
     *                     description="Success message",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request. Invalid email or missing required fields",
     *         @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Method not allowed. Honeypot field is filled",
     *         @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error. Email sending failed",
     *         @OA\MediaType(mediaType="application/json")
     *     )
     * )
     */
    public function create() {
        $body = json_decode(file_get_contents('php://input'), true);

        if (empty($body['name']) || empty($body['email']) || empty($body['subject']) || empty($body['message'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body']);
            exit;
        }

        $name = $body['name'];
        $email = $body['email'];
        $subject = $body['subject'];
        $message = $body['message'];

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

    /**
     * @OA\Get(
     *     path="/api/contact",
     *     summary="Get the contact page",
     *     tags={"Contact"},
     *     @OA\Response(
     *         response=200,
     *         description="Contact page",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     description="The title of the page",
     *                     type="string",
     *                     example="Contact Us"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function get() {
        ResponseHandler::getResponseHandler()->sendResponse(200, [
            'title' => 'Contact Us',
        ]);
    }
}
