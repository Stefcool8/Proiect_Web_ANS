<?php

namespace App\utils;

use Exception;

class JsonUtil {
    private static ?JsonUtil $jsonUtil = null;

    private function __construct() {}

    public static function getJsonUtil(): JsonUtil {
        if (self::$jsonUtil == null) {
            self::$jsonUtil = new JsonUtil();
        }

        return self::$jsonUtil;
    }

    public function filtrateAfterYearsAndColumns(array $years, array $columns, array $values): string {
        $data = [];

        foreach ($years as $year) {
            try {
                // get json data
                $json = CSVParser::getCSVParser()->getJsonRowFormat("../public/assets/csv/$year.csv");

                // get header
                $header = CSVParser::getCSVParser()->getHeader("../public/assets/csv/$year.csv");

                // decode json
                $jsonArray = json_decode($json, true);

                // extract rows after columns and their values
                if ($columns != null) {
                    $filteredArray = array_filter($jsonArray, function($row) use ($columns, $values, $header) {
                        foreach ($columns as $index => $columnIndex) {
                            $column = $header[$columnIndex];
                            if ($row[$column] != $values[$index]) {
                                return false;
                            }
                        }

                        return true;
                    });
                    // add filtered rows to data
                    $data = array_merge($data, $filteredArray);
                } else {
                    // add all rows to data
                    $data = array_merge($data, $jsonArray);
                }
            } catch (Exception $e) {
                echo "Error parsing CSV file: " . $e->getMessage();
                return "";
            }
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function extractTotalByYear(array $years, array $filterColumns, array $filterValues, int $dataColumn): string {
        $resultArray = []; // Initialize an empty array
        foreach ($years as $year) {
            $currentJson = JsonUtil::getJsonUtil()->filtrateAfterYearsAndColumns([$year], $filterColumns, $filterValues);
            $currentTotal = JsonUtil::getJsonUtil()->extractTotalPerDistinctColumnValue($currentJson, $dataColumn);
            $totalFromJSON = JsonUtil::getJsonUtil()->extractTotalFromJSON($currentTotal);
            $resultArray[$year] = $totalFromJSON; // Assign the value to the array with the key $year
        }
        return json_encode($resultArray,JSON_PRETTY_PRINT);
    }

    public function extractTotalPerDistinctColumnValue(string $json, int $column): string {
        $jsonArray = json_decode($json, true);

        // if jsonArray is empty return empty json
        if (count($jsonArray) == 0) {
            return json_encode([]);
        }

        $data = [];
        // get header from json
        $header = array_keys($jsonArray[0]);
        // get total column
        $totalColumn = $header[count($header) - 1];

        // extract the total per distinct column value
        foreach ($jsonArray as $row) {
            if (!isset($data[$row[$header[$column]]])) {
                $data[$row[$header[$column]]] = 0;
            }
            $data[$row[$header[$column]]] += $row[$totalColumn];
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function extractTotalFromJSON(string $json): int {
        $jsonArray = json_decode($json, true);
        $total = 0;

        // extract the total per distinct column value
        foreach ($jsonArray as $row) {
            $total = $row; // Assign $row directly to $total
        }

        return $total;
    }

    public function extractColumns(string $json, array $columns): string {
        $jsonArray = json_decode($json, true);
        $data = [];

        // get header from json
        $header = array_keys($jsonArray[0]);

        // extract the columns from each row
        foreach ($jsonArray as $row) {
            foreach ($columns as $column) {
                $data[$header[$column]][] = $row[$header[$column]];
            }
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}