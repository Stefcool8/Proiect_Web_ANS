<?php 

namespace App\utils;

use InvalidArgumentException;

class JWT {

    private static ?JWT $jwt = null;

    private function __construct() {}

    public static function getJWT(): JWT {
        if (self::$jwt === null) {
            self::$jwt = new JWT();
        }
        return self::$jwt;
    }

    public static function encode(array $payload): string {
        
        $secret = "secret";

        $header = json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]);
        $header = self::base64urlEncode($header);
        $payload = self::base64urlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", $secret, true);
        $signature = self::base64urlEncode($signature);

        return "$header.$payload.$signature";
    }

    private static function base64urlEncode(string $data): string {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public static function decode(string $token): array {
        
        if (preg_match("/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/", $token, $matches) !== 1) {
            throw new InvalidArgumentException('Invalid token format');
        }

        $signature = hash_hmac('sha256', "$matches[header].$matches[payload]", 'secret', true);

        $signatureFromToken = self::base64urlDecode($matches['signature']);

        if (!hash_equals($signature, $signatureFromToken)) {
            throw new InvalidArgumentException('Invalid signature');
        }

        $payload = self::base64urlDecode($matches['payload']);

        return json_decode($payload, true);
    }

    private static function base64urlDecode(string $data): string {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
    
}