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
        $expected = "NAME,AGE,CITY
        JOHN,30,NEW YORK
        JANE,25,LONDON";

        $csvParser = CSVParser::getCSVParser();

        // Act
        $csvParser->sanitizeCSV($csvFile);
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
        $result = $csvParser->getJsonRowFormat($csvFile);

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
        $result = $csvParser->getJsonColumnFormat($csvFile);

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
        $result = $csvParser->getJsonFromColumn($csvFile, $columnNumber);

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
        $result = $csvParser->getJsonFromRow($csvFile, $rowNumber);

        // Assert
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testGetHeader_ReturnsArrayOfColumnHeaders()
    {
        // Arrange
        $csvFile = 'testFiles/test.csv';
        $expected = ['NAME', 'AGE', 'CITY'];

        $csvParser = CSVParser::getCSVParser();

        // Act
        $result = $csvParser->getHeader($csvFile);

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
        $expected = 'NAME,AGE,CITY,COUNTRY
JOHN,30,NEW YORK,USA
JANE,25,LONDON,USA';

        $csvParser = CSVParser::getCSVParser();

        // Act
        $csvParser->addColumn($csvFile, $newColumnIndex, $newColumnName, $newColumnValue);
        $result = file_get_contents($csvFile);

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testGetCSVParser_KeepsSameInstance()
    {
        // Arrange
        $csvParser1 = CSVParser::getCSVParser();
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
