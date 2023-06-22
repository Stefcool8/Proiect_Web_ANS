<?php

namespace utils;

use App\utils\ResponseHandler;
use PHPUnit\Framework\TestCase;

class ResponseHandlerTest extends TestCase
{
    public function testGetResponseHandler_ReturnsInstanceOfResponseHandler() {
        // Arrange
        $expected = ResponseHandler::class;

        // Act
        /** @var ResponseHandler|mixed $actual */
        $actual = ResponseHandler::getResponseHandler();

        // Assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testSendResponse_SendsCorrectResponse()
    {
        // Arrange
        $statusCode = 200;
        $data = ['message' => 'Success'];
        $response = [
            "status_code" => $statusCode,
            "data" => $data
        ];
        /** @var ResponseHandler|mixed $expectedResponse */
        $expectedResponse = json_encode($response);

        // Act
        ob_start();
        ResponseHandler::sendResponse($statusCode, $data);
        /** @var string|mixed $output */
        $output = ob_get_clean();

        // Assert
        $this->assertJson($output);
        $this->assertEquals($expectedResponse, $output);
    }

    public function testSendResponse_SendsCorrectResponseWithDifferentStatusCode()
    {
        // Arrange
        $statusCode = 400;
        $data = ['error' => 'Bad request'];
        $response = [
            "status_code" => $statusCode,
            "data" => $data
        ];
        /** @var ResponseHandler|mixed $expectedResponse */
        $expectedResponse = json_encode($response);

        // Act
        ob_start();
        ResponseHandler::sendResponse($statusCode, $data);
        /** @var string|mixed $output */
        $output = ob_get_clean();

        // Assert
        $this->assertJson($output);
        $this->assertEquals($expectedResponse, $output);
    }

    public function testSendResponse_SendsCorrectResponseWithDifferentData()
    {
        // Arrange
        $statusCode = 200;
        $data = ['message' => 'Success', 'data' => ['id' => 1, 'name' => 'John Doe']];
        $response = [
            "status_code" => $statusCode,
            "data" => $data
        ];
        /** @var ResponseHandler|mixed $expectedResponse */
        $expectedResponse = json_encode($response);

        // Act
        ob_start();
        ResponseHandler::sendResponse($statusCode, $data);
        /** @var string|mixed $output */
        $output = ob_get_clean();

        // Assert
        $this->assertJson($output);
        $this->assertEquals($expectedResponse, $output);
    }

}