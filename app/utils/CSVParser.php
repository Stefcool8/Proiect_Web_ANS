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

    /**
     * @throws Exception
     */
    public function getJsonFromRows(string $csvFile): string {
        if ($handle = fopen($csvFile, 'r')) {
            // get column headers
            $header = fgetcsv($handle);

            $data = [];
            while ($row = fgetcsv($handle)) {
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
    public function getJsonFromColumns(string $csvFile): array {
        try {
            $rows = json_decode($this->getJsonFromRows($csvFile), true);

            // transpose rows to columns
            $columns = [];
            foreach ($rows as $row) {
                foreach ($row as $key => $value) {
                    $columns[$key][] = $value;
                }
            }

            return $columns;
        } catch (Exception $e) {
            throw new Exception("Error parsing CSV file: " . $e->getMessage());
        }
    }
}
