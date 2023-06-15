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
                "\"name\",\"age\",\"city\"": "\"John\",\"30\",\"New York\""
            },
            {
                "\"name\",\"age\",\"city\"": "\"Jane\",\"25\",\"London\""
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
            "\"name\",\"age\",\"city\"": [
                "\"John\",\"30\",\"New York\"",
                "\"Jane\",\"25\",\"London\""
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
        $columnName = 'name';
        $expected = '[
            "John",
            "Jane"
        ]';

        $csvParser = CSVParser::getCSVParser();

        // Act
        try {
            /** @var string|mixed $result */
            $result = $csvParser->getJsonFromColumn($csvFile, $columnName);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testGetJsonFromRow_ReturnsValidJsonString()
    {
        // Arrange
        $csvFile = 'test.csv';
        $rowNumber = 1;
        $expected = '{
        "name": "Jane",
        "age": "25",
        "city": "London"
    }';

        $csvParser = CSVParser::getCSVParser();

        // Act
        /** @var string|mixed $result */
        $result = $csvParser->getJsonFromRow($csvFile, $rowNumber);

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
        $columnName = 'name';

        $csvParser = CSVParser::getCSVParser();

        // Act
        $csvParser->getJsonFromColumn($csvFile, $columnName);
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
