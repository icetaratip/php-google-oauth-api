<?php

declare(strict_types=1);

namespace App\Core;

use App\Config\Env;

class JWT
{
    private static ?string $secret = null;

    private static function getSecret(): string
    {
        if (self::$secret === null) {
            self::$secret = (string)Env::get('JWT_SECRET', 'a_very_long_random_fallback_secret_key_123456');
        }
        return self::$secret;
    }

    public static function encode(array $payload, int $expiry = 86400): string
    {
        $payload['exp'] = time() + $expiry;
        
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSecret(), true);
        $base64UrlSignature = self::base64UrlEncode($signature);
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function decode(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }
        
        list($header, $payload, $signature) = $parts;
        
        $validSignature = self::base64UrlEncode(hash_hmac('sha256', $header . "." . $payload, self::getSecret(), true));
        
        if (!hash_equals($validSignature, $signature)) {
            return null;
        }
        
        $payloadDecoded = json_decode(self::base64UrlDecode($payload), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        
        if (isset($payloadDecoded['exp']) && $payloadDecoded['exp'] < time()) {
            return null;
        }
        
        return $payloadDecoded;
    }

    private static function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}
