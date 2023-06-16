<?php

namespace App\Utils;

use PDO;
use PDOException;
use PDOStatement;

class Database {
private static ?Database $instance = null;
    private ?PDO $connection;

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
        //make parameter bindind
        //This ensures that the values are treated as data and not as executable SQL code. 
        //Binding the parameters correctly prevents SQL injection by automatically handling proper escaping and quoting of the input values.
        foreach ($params as $param => $value) {
            $stmt->bindValue(':' . $param, $value);
        }
        $stmt->execute();
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

        //fn - shorthand for anonymus function 
        $params = array_combine(array_map(fn($col) => ":$col", array_keys($data)), $data);

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $params);
    }

    public function update(string $table, array $data, array $conditions): void {
        $set = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $where = implode(' AND ', array_map(fn($col) => "$col = :{$col}_cond", array_keys($conditions)));
        $sql = "UPDATE $table SET $set WHERE $where";

        $params = array_merge(
            array_combine(array_map(fn($col) => ":$col", array_keys($data)), $data),
            array_combine(array_map(fn($col) => ":{$col}_cond", array_keys($conditions)), $conditions)
        );

        $this->query($sql, (array)$params);
    }

    public function join(string $table1, string $table2, array $joinConditions, array $params = []): array {
        $joinClauses = [];
        foreach ($joinConditions as $condition) {
            $joinClauses[] = $condition['table1Column'] . ' ' . $condition['operator'] . ' ' . $condition['table2Column'];
        }
        $joinClause = implode(' AND ', $joinClauses);
    
        $sql = "SELECT * FROM $table1 JOIN $table2 ON $joinClause";
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
