<?php

namespace App\controllers;

use App\utils\JsonUtil;
use App\utils\CSVParser;
use Exception;

class TestController {
    public function test() {
        try {
            //$json = CSVParser::getCSVParser()->getHeader("../public/assets/csv/2019.csv");
            $json = JsonUtil::getJsonUtil()->filtrateAfterYearsAndColumns(["2012"], [1], ["AUTOBUZ"]);

            $json = JsonUtil::getJsonUtil()->extractTotalPerDistinctColumnValue($json, 3);

            // save json to file
            //file_put_contents("../public/assets/csv/test.json", $json);

            var_dump($json);
        } catch (Exception $e) {
            echo "Error parsing CSV file: " . $e->getMessage();
            return;
        }
    }
}