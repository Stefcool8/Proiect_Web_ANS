<?php

namespace App\Models;

use App\Utils\Database;

class User {
    private int $id;
    private string $username;
    private string $email;
    private Database $db;

    public function __construct(int $id, string $username, string $email) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->db = Database::getInstance();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function login(string $username, string $password): bool {
        // Login logic
    }

    public function logout(): void {
        // Logout logic
    }

    public function register(string $username, string $email, string $password): void {
        // Register logic
    }

    public function update(string $username, string $email, string $password): void {
        $data = [
            'username' => $username,
            'email' => $email,
            // 'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $conditions = ['id' => $this->id];
        $this->db->update('users', $data, $conditions);

        $this->username = $username;
        $this->email = $email;
    }

    public function delete(): void {
        $conditions = ['id' => $this->id];
        $this->db->delete('users', $conditions);
    }

    public static function findById(int $id): ?User {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $db->fetchOne($sql, ['id' => $id]);

        if ($result) {
            return new User($result['id'], $result['username'], $result['email']);
        }

        return null;
    }
}
