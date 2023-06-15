<?php

namespace utils;

use App\utils\JWT;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{
    public function testGetJWT_ReturnsInstanceOfJWT()
    {
        // Arrange
        $expected = JWT::class;

        // Act
        /** @var JWT|mixed $actual */
        $actual = JWT::getJWT();

        // Assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testEncodeReturnsValidJWT()
    {
        $payload = [
            'sub' => '1234567890',
            'name' => 'John Doe',
            'iat' => 1516239022
        ];

        $expectedHeader = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ';
        $expectedSignature = 'XbPfbIHMI6arZ3Y922BhjWgQzWXcXNrz0ogtVhfEd2o';

        $jwt = JWT::getJWT();

        /** @var string|mixed $result */
        $result = $jwt->encode($payload);
        /** @var string|mixed $expectedJWT */
        $expectedJWT = "$expectedHeader.$expectedPayload.$expectedSignature";

        $this->assertEquals($expectedJWT, $result);
    }

    public function testDecodeReturnsValidPayload()
    {
        $header = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';
        $payload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ';
        $signature = 'XbPfbIHMI6arZ3Y922BhjWgQzWXcXNrz0ogtVhfEd2o';

        $jwt = "$header.$payload.$signature";

        /** @var array|mixed $expectedPayload */
        $expectedPayload = [
            'sub' => '1234567890',
            'name' => 'John Doe',
            'iat' => 1516239022
        ];

        /** @var array|mixed $result */
        $result = JWT::getJWT()->decode($jwt);

        $this->assertEquals($expectedPayload, $result);
    }

    public function testDecodeThrowsExceptionForInvalidTokenFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid token format');

        $jwt = 'invalid_token';

        JWT::getJWT()->decode($jwt);
    }

    public function testDecodeThrowsExceptionForInvalidSignature()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid signature');

        $jwt = 'eyJhbGciOiAiSFMyNTYiLCAidHlwIjogIkpXVCJ9.eyJzdWIiOiAiMTIzNDU2Nzg5MCIsICJuYW1lIjogIkpvaG4gRG9lIiwgImlhdCI6IDE1MTYyMzkwMjJ9.invalid_signature';

        JWT::getJWT()->decode($jwt);
    }
}
