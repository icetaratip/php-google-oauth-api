<?php

declare(strict_types=1);

namespace App\Models;

class User
{
    public ?int $id = null;
    public string $googleId;
    public string $name;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public string $email;
    public int $emailVerified = 0;
    public ?string $picture = null;
    public ?string $locale = null;
    public ?string $lastLoginAt = null;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->googleId = $data['google_id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->firstName = $data['first_name'] ?? null;
        $this->lastName = $data['last_name'] ?? null;
        $this->email = $data['email'] ?? '';
        $this->emailVerified = isset($data['email_verified']) ? (int)$data['email_verified'] : 0;
        $this->picture = $data['picture'] ?? null;
        $this->locale = $data['locale'] ?? null;
        $this->lastLoginAt = $data['last_login_at'] ?? null;
        $this->createdAt = $data['created_at'] ?? '';
        $this->updatedAt = $data['updated_at'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'google_id' => $this->googleId,
            'name' => $this->name,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'email_verified' => $this->emailVerified,
            'picture' => $this->picture,
            'locale' => $this->locale,
            'last_login_at' => $this->lastLoginAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
