<?php

namespace controllers;


use App\utils\Database;
use PHPUnit\Framework\TestCase;
use App\controllers\UserController;
use App\utils\ResponseHandler;

class UserControllerTest extends TestCase
{
    public function testCreateUserSuccess()
    {
        // Mock the necessary dependencies
        $dbMock = $this->createMock(Database::class);
        $dbMock->expects($this->once())
            ->method('fetchOne')
            ->willReturn(false);
        $dbMock->expects($this->once())
            ->method('insert');

        // Create an instance of the UserController with the mocked dependencies
        $userController = new UserController($dbMock);

        // Set up the request body
        $requestBody = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'SecureP@ssword123',
            'username' => 'johnDoe'
        ];

        // Set up the expected response
        $expectedResponse = [
            "status_code" => 200,
            "message" => "User created successfully"
        ];

        // Send the request and get the actual response
        ob_start();
        $userController->create(json_encode($requestBody));
        $actualResponse = json_decode(ob_get_contents(), true);
        ob_end_clean();

        // Assert the response
        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
