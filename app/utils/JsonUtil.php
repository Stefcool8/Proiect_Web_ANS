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

    public static function filtrateAfterYearsAndColumns(array $years, array $columns, array $values): string {
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
            } catch (Exception $e) {
                echo "Error parsing CSV file: " . $e->getMessage();
                return "";
            }
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}