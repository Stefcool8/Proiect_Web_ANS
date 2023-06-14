<?php
// DONE
namespace App\controllers;

use App\utils\ResponseHandler;
use App\utils\EmailSender;

/**
 * Controller for the Contact page.
 *
 */
class ContactController {
    /**
     * @OA\Post(
     *     path="/api/contact",
     *     summary="Send contact request to admin",
     *     operationId="contact",
     *     tags={"Contact"},
     *     @OA\RequestBody(
     *          description="Contact request",
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="name", type="string", example="John Doe"),
     *          @OA\Property(property="email", type="string", example="
     *         @OA\Property(property="subject", type="string", example="Subject"),
     *     @OA\Property(property="message", type="string", example="Message"),
     *     @OA\Property(property="honeypot", type="string", example=""),
     *     )
     * ),
     *     @OA\Response(
     *     response=200,
     *     description="Contact request sent successfully",
     *     @OA\JsonContent(
     *     @OA\Property(property="status_code", type="integer", example=200),
     *     @OA\Property(property="message", type="string", example="Contact request sent successfully")
     *   )
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Invalid request body",
     *     @OA\JsonContent(
     *     @OA\Property(property="status_code", type="integer", example=400),
     *     @OA\Property(property="error", type="string", example="Invalid request body")
     *  )
     * ),
     *     @OA\Response(
     *     response=405,
     *     description="Invalid request",
     *     @OA\JsonContent(
     *     @OA\Property(property="status_code", type="integer", example=405),
     *     @OA\Property(property="error", type="string", example="Invalid request")
     * )
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *     @OA\Property(property="status_code", type="integer", example=500),
     *     @OA\Property(property="error", type="string", example="Internal Server Error")
     * )
     * )
     * )
     */
    public function create() {
        $body = json_decode(file_get_contents('php://input'), true);

        // Check if honeypot is filled
        if (!empty($body['nickname'])) {
            ResponseHandler::getResponseHandler()->sendResponse(405, ['error' => 'Invalid request']);
            exit;
        }

        // validate required fields
        if (empty($body['name']) || empty($body['email']) || empty($body['subject']) || empty($body['message'])) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body']);
            exit;
        }
        // validate email
        if (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            ResponseHandler::getResponseHandler()->sendResponse(400, ['error' => 'Invalid request body']);
            exit;
        }

        // get data from request body
        $name = $body['name'];
        $email = $body['email'];
        $subject = $body['subject'];
        $message = $body['message'];

        // build email template
        $template = file_get_contents('../public/assets/templates/contact.html');
        $template = str_replace('{name_placeholder}', $name, $template);
        $template = str_replace('{email_placeholder}', $email, $template);
        $template = str_replace('{subject_placeholder}', $subject, $template);
        $template = str_replace('{message_placeholder}', $message, $template);

        // send email to client
        $clientEmailSent = EmailSender::getEmailSender()->sendEmail($email, $name, 'Contact request submitted at ANS', $template);
        if (!$clientEmailSent) {
            exit;
        }

        // send email to admin
        $adminEmailSent = EmailSender::getEmailSender()->sendEmail('ans.web.mail10@gmail.com', 'ANS Web', '[ANS] Contact request', $template);
        if ($adminEmailSent) {
            ResponseHandler::getResponseHandler()->sendResponse(200, ['message' => 'Contact request submitted successfully']);
        }
    }
}
