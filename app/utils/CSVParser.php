<?php

namespace App\utils;

use Exception;

class CSVParser {
    private static ?CSVParser $csvParser = null;

    private function __construct() {}

    public static function getCSVParser(): CSVParser {
        if (self::$csvParser == null) {
            self::$csvParser = new CSVParser();
        }

        return self::$csvParser;
    }

    function sanitizeCSV(string $csvFile): void {
        $data = file_get_contents($csvFile);

        // Remove "\"
        $data = str_replace("\\", "", $data);

        // Replace all ";" with ","
        $data = str_replace(";", ",", $data);

        // Write the sanitized data back to the file
        file_put_contents($csvFile, $data);
    }

    /**
     * @throws Exception
     */
    public function getJsonRowFormat(string $csvFile): string {
        if ($handle = fopen($csvFile, 'r')) {
            // get column headers
            $header = fgetcsv($handle, 1000);
            // remove BOM from headers
            $header = array_map(function($element) {
                return trim($element, "\xEF\xBB\xBF");
            }, $header);

            $data = [];
            while ($row = fgetcsv($handle, 1000)) {
                $data[] = array_combine($header, $row);
            }

            fclose($handle);

            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            throw new Exception("Error opening CSV file.");
        }
    }

    /**
     * @throws Exception
     */
    public function getJsonColumnFormat(string $csvFile): string {
        try {
            $rowsJson = $this->getJsonRowFormat($csvFile);
            $rows = json_decode($rowsJson, true);

            if ($rows === null) {
                throw new Exception("Error decoding JSON: " . json_last_error_msg());
            }

            // transpose rows to columns
            $columns = [];
            foreach ($rows as $row) {
                foreach ($row as $key => $value) {
                    $columns[$key][] = $value;
                }
            }

            return json_encode($columns, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            throw new Exception("Error parsing CSV file: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getJsonFromColumn(string $csvFile, int $columnNumber): string {
        try {
            $columns = json_decode($this->getJsonColumnFormat($csvFile), true);
            $array = array_values($columns);
            return json_encode($array[$columnNumber], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            throw new Exception("Error parsing CSV file: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getJsonFromRow(string $csvFile, int $rowNumber): string {
        try {
            $rows = json_decode($this->getJsonRowFormat($csvFile), true);
            $array = array_values($rows);
            return json_encode($array[$rowNumber], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            throw new Exception("Error parsing CSV file: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getHeader(string $csvFile): array {
        if ($handle = fopen($csvFile, 'r')) {
            // get column headers
            $header = fgetcsv($handle, 1000);
            // remove BOM from headers
            $header = array_map(function($element) {
                return trim($element, "\xEF\xBB\xBF");
            }, $header);

            fclose($handle);

            return $header;
        } else {
            throw new Exception("Error opening CSV file.");
        }
    }
}
