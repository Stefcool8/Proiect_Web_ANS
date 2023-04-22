<?php

namespace App\Utils;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';
    private $dbname = 'ans';
    private $username = 'root';
    private $password = '';

    private $connection;

    /**
     * Constructor for the Database class
     * 
     * @return void
     */
    private function __construct() {
        try {
            $this->connection = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->dbname,
                $this->username,
                $this->password
            );

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
    }

    /**
     * Method for preparing any type of query
     * 
     * @param string $sql SQL query
     * @param array $params Parameters for the query
     * @return PDOStatement The prepared statement
     */
    private function query(string $sql, array $params = []): PDOStatement {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            return $stmt;
        } catch (PDOException $e) {
            echo 'Query error: ' . $e->getMessage();
        }
    }

    /**
     * Method for executing SELECT queries
     * 
     * @param string $sql SQL query
     * @param array $params Parameters for the query
     * @return array The results of the query as an associative array
     */
    public function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Method for executing SELECT queries
     * 
     * @param string $sql SQL query
     * @param array $params Parameters for the query
     * @return array The result of the query as an associative array
     */
    public function fetchOne(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Method for executing INSERT queries
     * 
     * @param string $table The table to insert into
     * @param array $data The data to insert
     * @return void
     */
    public function insert(string $table, array $data): void {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $this->query($sql, $data);
    }

    /**
     * Method for executing UPDATE queries
     * 
     * @param string $table The table to update
     * @param array $data The data to update
     * @param array $conditions The conditions for the query
     * @return void
     */
    public function update(string $table, array $data, array $conditions): void {
        $set = '';
        foreach ($data as $column => $value) {
            $set .= "{$column} = :{$column}, ";
        }
        $set = rtrim($set, ', ');

        $where = '';
        foreach ($conditions as $column => $value) {
            $where .= "{$column} = :{$column}_cond AND ";
        }
        $where = rtrim($where, ' AND ');

        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";

        // Prepare named parameters for both data and conditions
        $params = [];
        foreach ($data as $column => $value) {
            $params[":{$column}"] = $value;
        }
        foreach ($conditions as $column => $value) {
            $params[":{$column}_cond"] = $value;
        }

        $this->query($sql, $params);
    }


    /**
     * Method for executing DELETE queries
     * 
     * @param string $table The table to delete from
     * @param array $conditions The conditions for the query
     * @return void
     */
    public function delete(string $table, array $conditions): void {
        $where = '';
        foreach ($conditions as $column => $value) {
            $where .= "{$column} = :{$column} AND ";
        }
        $where = rtrim($where, ' AND ');

        $sql = "DELETE FROM {$table} WHERE {$where}";

        $this->query($sql, $conditions);
    }

    /**
     * Method for closing the connection
     * 
     * @return void
     */
    public function closeConnection(): void {
        $this->connection = null;
    }

    /**
     * Method for getting the database instance
     * 
     * @return Database The database instance
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}
