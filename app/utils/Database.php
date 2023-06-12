<?php

namespace App\utils;

use PDO;
use PDOException;
use PDOStatement;

class Database {
private static ?Database $instance = null;
    private PDO $connection;

    private function __construct() {
        $this->initConnection();
    }

    private function initConnection(): void {
        $host = 'localhost';
        $dbname = 'web';
        $username = 'root';
        $password = 'password';

        try {
            $this->connection = new PDO(
                'mysql:host=' . $host . ';dbname=' . $dbname,
                $username,
                $password
            );

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
    }

    private function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function insert(string $table, array $data): void {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $data);
    }

    public function delete(string $table, array $conditions): void {
        $where = implode(' AND ', array_map(fn($col) => "$col = :$col", array_keys($conditions)));
        $sql = "DELETE FROM $table WHERE $where";
        $this->query($sql, $conditions);
    }

    public function beginTransaction(): void {
        $this->connection->beginTransaction();
    }

    public function commit(): void {
        $this->connection->commit();
    }

    public function rollback(): void {
        $this->connection->rollBack();
    }

    public function closeConnection(): void {
        $this->connection = null;
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}
