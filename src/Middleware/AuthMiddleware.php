<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\JWT;

class AuthMiddleware
{
    public function execute(Request $request, Response $response): void
    {
        $token = $_COOKIE['jwt_token'] ?? null;
        
        if ($token) {
            $userPayload = JWT::decode($token);
            if ($userPayload) {
                $_SERVER['USER'] = $userPayload;
                return;
            }
        }
        
        if (isset($_COOKIE['jwt_token'])) {
            setcookie('jwt_token', '', time() - 3600, '/');
        }
        
        $uri = $request->getUri();
        if (strpos($uri, '/api/') === 0) {
            $response->json(['status' => 'error', 'message' => 'Unauthorized access.'], 401);
        } else {
            $response->redirect('/login');
        }
    }
}
