<?php

namespace utils;

use App\utils\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    protected static ?Database $database;

    public static function setUpBeforeClass(): void
    {
        // Initialize the database connection before running the tests
        self::$database = Database::getInstance();
    }

    public function testGetInstance_ReturnsInstanceOfDatabase(): void
    {
        // Arrange
        $expected = Database::class;

        // Act
        /** @var Database|mixed $actual */
        $actual = Database::getInstance();

        // Assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testFetchOne(): void
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $params = ['id' => 1];
        /** @var array|mixed $result */
        $result = self::$database->fetchOne($sql, $params);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('username', $result);
        $this->assertSame('Stefcool8', $result['username']);
    }

    public function testInsertAndDelete(): void
    {
        $table = 'users';
        $data = ['username' => 'test_user', 'email' => 'test@example.com'];

        // Insert a new row
        self::$database->insert($table, $data);

        // Fetch the inserted row
        $sql = "SELECT * FROM $table WHERE username = :username";
        $params = ['username' => 'test_user'];
        $result = self::$database->fetchOne($sql, $params);

        $this->assertNotNull($result);
        $this->assertSame('test@example.com', $result['email']);

        // Delete the inserted row
        self::$database->delete($table, ['username' => 'test_user']);

        // Verify that the row has been deleted
        $result = self::$database->fetchOne($sql, $params);
        $this->assertNull($result);
    }

    public static function tearDownAfterClass(): void
    {
        // Close the database connection after running the tests
        self::$database->closeConnection();
    }
}
