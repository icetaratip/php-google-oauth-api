<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use App\Models\User;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByGoogleId(string $googleId): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE google_id = :google_id');
        $stmt->execute(['google_id' => $googleId]);
        $row = $stmt->fetch();
        
        return $row ? new User($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        
        return $row ? new User($row) : null;
    }

    public function create(User $user): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (google_id, name, first_name, last_name, email, email_verified, picture, locale, last_login_at) 
             VALUES (:google_id, :name, :first_name, :last_name, :email, :email_verified, :picture, :locale, NOW())'
        );
        $stmt->execute([
            'google_id' => $user->googleId,
            'name' => $user->name,
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
            'email' => $user->email,
            'email_verified' => $user->emailVerified,
            'picture' => $user->picture,
            'locale' => $user->locale,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(User $user): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users 
             SET name = :name, 
                 first_name = :first_name, 
                 last_name = :last_name, 
                 email_verified = :email_verified, 
                 picture = :picture, 
                 locale = :locale 
             WHERE google_id = :google_id'
        );
        return $stmt->execute([
            'name' => $user->name,
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
            'email_verified' => $user->emailVerified,
            'picture' => $user->picture,
            'locale' => $user->locale,
            'google_id' => $user->googleId,
        ]);
    }

    public function updateLastLogin(int $userId): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        return $stmt->execute(['id' => $userId]);
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM users');
        return (int)$stmt->fetchColumn();
    }

    public function getLastLoginUser(): ?User
    {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY last_login_at DESC LIMIT 1');
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }
}
