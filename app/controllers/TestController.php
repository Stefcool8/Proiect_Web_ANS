<?php

namespace App\controllers;

use App\utils\CSVParser;
use Exception;

class TestController {
    public function test() {
        if (isset($_FILES['csv_file'])) {
            $csvFile = $_FILES['csv_file'];

            // Check if the file is uploaded successfully
            if ($csvFile['error'] === UPLOAD_ERR_OK) {
                // Parse the CSV file
                try {
                    $tmpFilePath = $csvFile['tmp_name'];

                    $json = CSVParser::getCSVParser()->getJsonFromColumns($tmpFilePath);
                    var_dump($json);
                } catch (Exception $e) {
                    echo "Error parsing CSV file: " . $e->getMessage();
                    return;
                }
            } else {
                echo "Error uploading CSV file: " . $csvFile['error'];
            }
        } else {
            echo "No CSV file uploaded";
        }
    }
}