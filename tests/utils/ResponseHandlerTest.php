<?php

namespace utils;

use App\utils\ResponseHandler;
use PHPUnit\Event\Runtime\PHPUnit;
use PHPUnit\Framework\MockObject\MockObject;
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
        $expectedResponse = [
            "status_code" => $statusCode,
            "data" => $data
        ];

        // Act
        ob_start();
        ResponseHandler::sendResponse($statusCode, $data);
        $output = ob_get_clean();

        // Assert
        $this->assertJson($output);
        $this->assertEquals(json_encode($expectedResponse), $output);
    }
}