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

    public function testSanitizeCSV_RemovesBackslashesAndReplacesSemicolons()
    {
        // Arrange
        $csvFile = 'testFiles/test.csv';
        /** @var string|mixed $expected */
        $expected = "NAME,AGE,CITY\nJOHN,30,\"NEW YORK\"\nJANE,25,LONDON";

        $csvParser = CSVParser::getCSVParser();

        // Act
        $csvParser->sanitizeCSV($csvFile);
        /** @var string|mixed $result */
        $result = file_get_contents($csvFile);

        // Assert
        $this->assertEquals($expected, $result);
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
            $result = $csvParser->getJsonRowFormat($csvFile);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
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
            $result = $csvParser->getJsonColumnFormat($csvFile);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
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
            $result = $csvParser->getJsonFromColumn($csvFile, $columnNumber);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
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
            "NAME": "JANE",
            "AGE": "25",
            "CITY": "LONDON"
        }';

        $csvParser = CSVParser::getCSVParser();

        // Act
        try {
            $result = $csvParser->getJsonFromRow($csvFile, $rowNumber);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        // Assert
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testGetHeader_ReturnsArrayOfColumnHeaders()
    {
        // Arrange
        $csvFile = 'testFiles/test.csv';
        /** @var array|mixed $expected */
        $expected = ['NAME', 'AGE', 'CITY'];

        $csvParser = CSVParser::getCSVParser();

        // Act
        try {
            /** @var array|mixed $result */
            $result = $csvParser->getHeader($csvFile);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testAddColumn_AddsNewColumnToCSVFile()
    {
        // Arrange
        $csvFile = 'testFiles/test.csv';
        $newColumnIndex = 3;
        $newColumnName = 'COUNTRY';
        $newColumnValue = 'USA';
        /** @var string|mixed $expected */
        $expected = "NAME,AGE,CITY,COUNTRY\nJOHN,30,\"NEW YORK\",USA\nJANE,25,LONDON,USA\n";

        // Read the original content of the file
        $originalContent = file_get_contents($csvFile);

        $csvParser = CSVParser::getCSVParser();

        // Act
        try {
            $csvParser->addColumn($csvFile, $newColumnIndex, $newColumnName, $newColumnValue);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        /** @var string|mixed $result */
        $result = file_get_contents($csvFile);

        // Rewrite the original content back to the file
        file_put_contents($csvFile, $originalContent);

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testGetCSVParser_KeepsSameInstance()
    {
        // Arrange
        $csvParser1 = CSVParser::getCSVParser();
        /** @var CSVParser|mixed $csvParser2 */
        $csvParser2 = CSVParser::getCSVParser();

        // Assert
        $this->assertSame($csvParser1, $csvParser2);
    }

    public function testGetJsonRowFormat_ThrowsExceptionForInvalidCSVFile()
    {
        // Arrange
        $csvFile = 'nonexistent.csv';

        $csvParser = CSVParser::getCSVParser();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error opening CSV file.');

        // Act
        $csvParser->getJsonRowFormat($csvFile);
    }

    public function testGetJsonColumnFormat_ThrowsExceptionForInvalidCSVFile()
    {
        // Arrange
        $csvFile = 'nonexistent.csv';

        $csvParser = CSVParser::getCSVParser();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error parsing CSV file: Error opening CSV file.');

        // Act
        $csvParser->getJsonColumnFormat($csvFile);
    }

    public function testGetJsonFromColumn_ThrowsExceptionForInvalidCSVFile()
    {
        // Arrange
        $csvFile = 'nonexistent.csv';
        $columnNumber = 1;

        $csvParser = CSVParser::getCSVParser();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error parsing CSV file: Error opening CSV file.');

        // Act
        $csvParser->getJsonFromColumn($csvFile, $columnNumber);
    }

    public function testGetJsonFromRow_ThrowsExceptionForInvalidCSVFile()
    {
        // Arrange
        $csvFile = 'nonexistent.csv';
        $rowNumber = 1;

        $csvParser = CSVParser::getCSVParser();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error parsing CSV file: Error opening CSV file.');

        // Act
        $csvParser->getJsonFromRow($csvFile, $rowNumber);
    }
}
