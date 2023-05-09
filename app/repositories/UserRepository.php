<?php

namespace App\Repositories;

use App\Models\User;
use App\Utils\Database;

class UserRepository {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?User {
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->db->fetchOne($sql, ['id' => $id]);

        if ($result) {
            return User::fromArray($result);
        }

        return null;
    }

    public function getByUsername(string $username): ?User {
        $sql = "SELECT * FROM user WHERE username = :username";
        $result = $this->db->fetchOne($sql, ['username' => $username]);
        
        if ($result) {
            return User::fromArray($result);
        }

        return null;
    }

    public function register(User $user): bool {
        
        $data = [
            'name' => $user->getName(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'password' => $user->getPassword()
        ];
        
        try {
            $this->db->beginTransaction();
            
            $this->db->insert('users', $data);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "Registration failed: " . $e->getMessage();
            return false;
        }
    }
}
