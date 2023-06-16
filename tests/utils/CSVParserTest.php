<?php

namespace utils;

use App\utils\CSVParser;
use Exception;
use PHPUnit\Framework\TestCase;

class CSVParserTest extends TestCase
{
    public function testGetCSVParser_ReturnsInstanceOfCSVParser()
    {
        // Arrange
        $expected = CSVParser::class;

        // Act
        /** @var CSVParser|mixed $actual */
        $actual = CSVParser::getCSVParser();

        // Assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGetJsonRowFormat_ReturnsValidJsonString()
    {
        // Arrange
        $csvFile = 'testFiles/test.csv';
        $expected = '[
            {
                "NAME": "JOHN",
                "AGE": "30",
                "CITY": "NEW YORK"
            },
            {
                "NAME": "JANE",
                "AGE": "25",
                "CITY": "LONDON"
            }
        ]';

        $csvParser = CSVParser::getCSVParser();

        // Act
        try {
            /** @var string|mixed $result */
            $result = $csvParser->getJsonRowFormat($csvFile);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testGetJsonColumnFormat_ReturnsValidJsonString()
    {
        // Arrange
        $csvFile = 'testFiles/test.csv';
        $expected = '{
            "NAME": [
                "JOHN",
                "JANE"
            ],
            "AGE": [
                "30",
                "25"
            ],
            "CITY": [
                "NEW YORK",
                "LONDON"
            ]
        }';

        $csvParser = CSVParser::getCSVParser();

        // Act
        try {
            /** @var string|mixed $result */
            $result = $csvParser->getJsonColumnFormat($csvFile);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testGetJsonFromColumn_ReturnsValidJsonString()
    {
        // Arrange
        $csvFile = 'testFiles/test.csv';
        $columnNumber = 0;
        $expected = '[
            "JOHN",
            "JANE"
        ]';

        $csvParser = CSVParser::getCSVParser();

        // Act
        try {
            /** @var string|mixed $result */
            $result = $csvParser->getJsonFromColumn($csvFile, $columnNumber);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testGetJsonFromRow_ReturnsValidJsonString()
    {
        // Arrange
        $csvFile = 'testFiles/test.csv';
        $rowNumber = 1;
        $expected = '{
            "AGE": "25",
            "CITY": "LONDON",
            "NAME": "JANE"
        }';

        $csvParser = CSVParser::getCSVParser();

        // Act
        try {
            /** @var string|mixed $result */
            $result = $csvParser->getJsonFromRow($csvFile, $rowNumber);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testGetJsonRowFormat_ThrowsExceptionForInvalidCSVFile()
    {
        // Arrange
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error opening CSV file.');

        $csvFile = 'nonexistent.csv';

        $csvParser = CSVParser::getCSVParser();

        // Act
        $csvParser->getJsonRowFormat($csvFile);
    }

    public function testGetJsonColumnFormat_ThrowsExceptionForInvalidCSVFile()
    {
        // Arrange
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error parsing CSV file: Error opening CSV file.');

        $csvFile = 'nonexistent.csv';

        $csvParser = CSVParser::getCSVParser();

        // Act
        $csvParser->getJsonColumnFormat($csvFile);
    }

    public function testGetJsonFromColumn_ThrowsExceptionForInvalidCSVFile()
    {
        // Arrange
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error parsing CSV file: Error opening CSV file.');

        $csvFile = 'nonexistent.csv';
        $columnNumber = 1;

        $csvParser = CSVParser::getCSVParser();

        // Act
        $csvParser->getJsonFromColumn($csvFile, $columnNumber);
    }

    public function testGetJsonFromRow_ThrowsExceptionForInvalidCSVFile()
    {
        // Arrange
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error parsing CSV file: Error opening CSV file.');

        $csvFile = 'nonexistent.csv';
        $rowNumber = 1;

        $csvParser = CSVParser::getCSVParser();

        // Act
        $csvParser->getJsonFromRow($csvFile, $rowNumber);
    }
}
