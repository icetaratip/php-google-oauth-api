<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\JWT;
use App\Services\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(Request $request, Response $response): void
    {
        $token = $_COOKIE['jwt_token'] ?? null;
        if ($token && JWT::decode($token)) {
            $response->redirect('/');
            return;
        }

        $this->render('auth/login', [
            'title' => 'Login - Google Identity'
        ]);
    }

    public function googleLoginCallback(Request $request, Response $response): void
    {
        $body = $request->getBody();
        
        $cookieCsrf = $_COOKIE['g_csrf_token'] ?? '';
        $postCsrf = $body['g_csrf_token'] ?? '';

        if (empty($cookieCsrf) || empty($postCsrf) || $cookieCsrf !== $postCsrf) {
            \App\Core\Session::setFlash('error', 'Google Sign-In failed: CSRF token verification failed.');
            $response->redirect('/login');
            return;
        }

        $idToken = $body['credential'] ?? '';
        if (empty($idToken)) {
            \App\Core\Session::setFlash('error', 'Google Sign-In failed: Missing ID Token.');
            $response->redirect('/login');
            return;
        }

        $googlePayload = $this->authService->verifyGoogleIdToken($idToken);
        if (!$googlePayload) {
            \App\Core\Session::setFlash('error', 'Google Sign-In failed: Invalid Google ID Token.');
            $response->redirect('/login');
            return;
        }

        $jwt = $this->authService->authenticateGoogleUser($googlePayload);
        if (!$jwt) {
            \App\Core\Session::setFlash('error', 'Google Sign-In failed: Authentication process failed.');
            $response->redirect('/login');
            return;
        }

        $expiry = time() + (int)\App\Config\Env::get('JWT_EXPIRY', 86400);
        setcookie('jwt_token', $jwt, $expiry, '/', '', false, true);

        $response->redirect('/');
    }

    public function logout(Request $request, Response $response): void
    {
        if (isset($_COOKIE['jwt_token'])) {
            setcookie('jwt_token', '', time() - 3600, '/');
        }
        $response->redirect('/login');
    }

    protected function render(string $viewName, array $data = []): void
    {
        $data['baseUrl'] = \App\Config\Env::get('APP_URL', 'http://localhost/Login-Google');
        $data['user'] = $_SERVER['USER'] ?? null;
        
        extract($data);
        
        $viewPath = dirname(__DIR__, 2) . "/views/{$viewName}.php";
        $layoutPath = dirname(__DIR__, 2) . "/views/layouts/app.php";
        
        if (file_exists($viewPath) && file_exists($layoutPath)) {
            ob_start();
            require $viewPath;
            $content = ob_get_clean();
            require $layoutPath;
        } else {
            echo "View [{$viewName}] not found.";
        }
    }
}
