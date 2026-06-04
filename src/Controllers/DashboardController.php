<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\UserRepository;

class DashboardController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function index(Request $request, Response $response): void
    {
        $userSession = $_SERVER['USER'] ?? null;
        $dbUser = null;
        
        if ($userSession && isset($userSession['google_id'])) {
            $dbUser = $this->userRepository->findByGoogleId($userSession['google_id']);
        }

        $this->render('dashboard/index', [
            'title' => 'Dashboard - Google Account Manager',
            'activeMenu' => 'dashboard',
            'user' => $dbUser ? $dbUser->toArray() : null
        ]);
    }

    public function stats(Request $request, Response $response): void
    {
        usleep(500000);

        try {
            $totalUsers = $this->userRepository->countAll();
            $lastUser = $this->userRepository->getLastLoginUser();
            
            $lastLoginName = $lastUser ? $lastUser->name : 'N/A';
            $lastLoginTime = $lastUser && $lastUser->lastLoginAt ? $lastUser->lastLoginAt : 'N/A';
            
            $response->json([
                'status' => 'success',
                'data' => [
                    'total_users' => $totalUsers,
                    'last_login_name' => $lastLoginName,
                    'last_login_time' => $lastLoginTime
                ]
            ]);
        } catch (\Exception $e) {
            $response->json([
                'status' => 'error',
                'message' => 'Failed to fetch statistics.'
            ], 500);
        }
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
