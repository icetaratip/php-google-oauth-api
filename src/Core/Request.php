<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        $basePath = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
        if (strpos($uri, $basePath) === 0) {
            $route = substr($uri, strlen($basePath));
        } else {
            $route = $uri;
        }

        return '/' . trim($route, '/');
    }

    public function getBody(): array
    {
        $body = [];
        
        if ($this->getMethod() === 'GET') {
            foreach ($_GET as $key => $val) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        
        if ($this->getMethod() === 'POST') {
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (strpos($contentType, 'application/json') !== false) {
                $json = file_get_contents('php://input');
                $body = json_decode($json, true) ?? [];
            } else {
                foreach ($_POST as $key => $val) {
                    if (is_array($val)) {
                        $body[$key] = $val;
                    } else {
                        $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        
        return $body;
    }
}
