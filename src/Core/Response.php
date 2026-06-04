<?php

declare(strict_types=1);

namespace App\Core;

use App\Config\Env;

class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $url): void
    {
        if (strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
            $baseUrl = Env::get('APP_URL', 'http://localhost/Login-Google');
            $url = rtrim($baseUrl, '/') . '/' . ltrim($url, '/');
        }
        header("Location: " . $url);
        exit;
    }

    public function json(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $this->setStatusCode($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
