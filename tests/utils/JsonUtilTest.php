<?php

namespace utils;

use App\utils\JsonUtil;
use PHPUnit\Framework\TestCase;

class JsonUtilTest extends TestCase {
    public function testFiltrateAfterYearsAndColumns(): void {
        $jsonUtil = JsonUtil::getJsonUtil();

        // Test case 1: Filtering with specific columns and values
        $years = [2020, 2021];
        $columns = [0, 1]; // Assuming column indexes are 0-based
        $values = ['value1', 'value2'];
        $expectedResult = '{"data":[{"column1":"value1","column2":"value2","column3":3},{"column1":"value1","column2":"value2","column3":6}]}';
        $result = $jsonUtil->filtrateAfterYearsAndColumns($years, $columns, $values);
        $this->assertEquals($expectedResult, $result);

        // Test case 2: Filtering with all rows
        $years = [2022];
        $columns = null;
        $values = [];
        $expectedResult = '{"data":[{"column1":"value3","column2":"value4","column3":9},{"column1":"value5","column2":"value6","column3":12}]}';
        $result = $jsonUtil->filtrateAfterYearsAndColumns($years, $columns, $values);
        $this->assertEquals($expectedResult, $result);

        // Add more test cases as needed
    }

    public function testExtractTotalByYear(): void {
        $jsonUtil = JsonUtil::getJsonUtil();

        // Test case 1: Extracting total by year with specific filter columns and values
        $years = [2020, 2021];
        $filterColumns = [0, 1]; // Assuming column indexes are 0-based
        $filterValues = ['value1', 'value2'];
        $dataColumn = 2; // Assuming the data column index is 2
        $expectedResult = '{"2020":9,"2021":18}';
        $result = $jsonUtil->extractTotalByYear($years, $filterColumns, $filterValues, $dataColumn);
        $this->assertEquals($expectedResult, $result);

        // Test case 2: Extracting total by year with no filter columns
        $years = [2022];
        $filterColumns = [];
        $filterValues = [];
        $dataColumn = 2; // Assuming the data column index is 2
        $expectedResult = '{"2022":21}';
        $result = $jsonUtil->extractTotalByYear($years, $filterColumns, $filterValues, $dataColumn);
        $this->assertEquals($expectedResult, $result);

        // Add more test cases as needed
    }

    public function testExtractTotalPerDistinctColumnValue(): void {
        $jsonUtil = JsonUtil::getJsonUtil();

        // Test case 1: Extracting total per distinct column value
        $json = '{"data":[{"column1":"value1","column2":"value2","column3":3},{"column1":"value1","column2":"value2","column3":6},{"column1":"value3","column2":"value4","column3":9},{"column1":"value5","column2":"value6","column3":12}]}';
        $column = 1; // Assuming the column index is 1
        $expectedResult = '{"value2":9,"value4":9,"value6":12}';
        $result = $jsonUtil->extractTotalPerDistinctColumnValue($json, $column);
        $this->assertEquals($expectedResult, $result);

        // Add more test cases as needed
    }

    public function testExtractTotalFromJSON(): void {
        $jsonUtil = JsonUtil::getJsonUtil();

        // Test case 1: Extracting total from JSON
        $json = '{"value1":9,"value2":18}';
        $expectedResult = 18;
        $result = $jsonUtil->extractTotalFromJSON($json);
        $this->assertEquals($expectedResult, $result);

        // Add more test cases as needed
    }

    public function testExtractColumns(): void {
        $jsonUtil = JsonUtil::getJsonUtil();

        // Test case 1: Extracting specific columns from JSON
        $json = '{"data":[{"column1":"value1","column2":"value2","column3":3},{"column1":"value1","column2":"value2","column3":6},{"column1":"value3","column2":"value4","column3":9},{"column1":"value5","column2":"value6","column3":12}]}';
        $columns = [0, 2]; // Assuming column indexes are 0-based
        $expectedResult = '{"column1":["value1","value1","value3","value5"],"column3":[3,6,9,12]}';
        $result = $jsonUtil->extractColumns($json, $columns);
        $this->assertEquals($expectedResult, $result);

        // Add more test cases as needed
    }
}
