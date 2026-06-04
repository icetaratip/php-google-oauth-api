<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Env;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Core\JWT;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function verifyGoogleIdToken(string $idToken): ?array
    {
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($idToken);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return null;
        }

        $payload = json_decode($response, true);
        if (!$payload || isset($payload['error_description'])) {
            return null;
        }

        $clientId = Env::get('GOOGLE_CLIENT_ID');
        if (isset($payload['aud']) && $payload['aud'] !== $clientId) {
            return null;
        }

        return $payload;
    }

    public function authenticateGoogleUser(array $googlePayload): ?string
    {
        $googleId = $googlePayload['sub'] ?? '';
        $email = $googlePayload['email'] ?? '';
        $name = $googlePayload['name'] ?? '';
        $firstName = $googlePayload['given_name'] ?? null;
        $lastName = $googlePayload['family_name'] ?? null;
        $emailVerified = isset($googlePayload['email_verified']) && ($googlePayload['email_verified'] === true || $googlePayload['email_verified'] === 'true') ? 1 : 0;
        $picture = $googlePayload['picture'] ?? null;
        $locale = $googlePayload['locale'] ?? null;

        if (empty($googleId) || empty($email)) {
            return null;
        }

        $user = $this->userRepository->findByGoogleId($googleId);

        if ($user) {
            $user->name = $name;
            $user->firstName = $firstName;
            $user->lastName = $lastName;
            $user->emailVerified = $emailVerified;
            $user->picture = $picture;
            $user->locale = $locale;
            
            $this->userRepository->update($user);
            $this->userRepository->updateLastLogin($user->id);
        } else {
            $newUser = new User([
                'google_id' => $googleId,
                'name' => $name,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'email_verified' => $emailVerified,
                'picture' => $picture,
                'locale' => $locale
            ]);
            $newId = $this->userRepository->create($newUser);
            $user = $this->userRepository->findByGoogleId($googleId);
        }

        if (!$user) {
            return null;
        }

        $tokenPayload = [
            'user_id' => $user->id,
            'google_id' => $user->googleId,
            'name' => $user->name,
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
            'email' => $user->email,
            'email_verified' => $user->emailVerified,
            'picture' => $user->picture,
            'locale' => $user->locale
        ];

        $expiry = (int)Env::get('JWT_EXPIRY', 86400);
        return JWT::encode($tokenPayload, $expiry);
    }
}
