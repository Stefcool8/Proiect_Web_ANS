<?php

namespace utils;

use App\utils\JsonUtil;
use PHPUnit\Framework\TestCase;

class JsonUtilTest extends TestCase {
    public function testFiltrateAfterYearsAndColumns(): void {
        $jsonUtil = JsonUtil::getJsonUtil();

        // Filtering with specific years, columns and values
        $years = ["2012"];
        $columns = [0, 1, 4];
        $values = ['ALBA', 'AUTOBUZ', 'DAILY'];
        $expectedResult = '[
    {
        "JUDET": "ALBA",
        "CATEGORIE_NATIONALA": "AUTOBUZ",
        "CATEGORIA_COMUNITARA": "M2",
        "MARCA": "IVECO",
        "DESCRIERE_COMERCIALA": "DAILY",
        "VALUE_NAME": "",
        "TOTAL": "18"
    }
]';
        // remove /r from expected result
        /** @var string|mixed $expectedResult */
        $expectedResult = str_replace("\r", "", $expectedResult);

        /** @var string|mixed $result */
        $result = $jsonUtil->filtrateAfterYearsAndColumns($years, $columns, $values);
        $this->assertEquals($expectedResult, $result);
    }

    public function testExtractTotalByYear(): void {
        $jsonUtil = JsonUtil::getJsonUtil();

        $years = [2020, 2021];
        $filterColumns = [0, 1];
        $filterValues = ['ALBA', 'AUTOBUZ'];
        $dataColumn = 0;
        $expectedResult = '{
    "2020": 416,
    "2021": 421
}';
        // remove /r from expected result
        /** @var string|mixed $expectedResult */
        $expectedResult = str_replace("\r", "", $expectedResult);

        /** @var string|mixed $result */
        $result = $jsonUtil->extractTotalByYear($years, $filterColumns, $filterValues, $dataColumn);
        $this->assertEquals($expectedResult, $result);
    }

    public function testExtractTotalPerDistinctColumnValue(): void {
        $jsonUtil = JsonUtil::getJsonUtil();
        $json = '[
{
"JUDET": "ALBA",
"TOTAL": "1"
},
{
"JUDET": "ALBA",
"TOTAL": "1"
}
]';
        $column = 0;
        $expectedResult = '{
    "ALBA": 2
}';
        // remove /r from expected result
        /** @var string|mixed $expectedResult */
        $expectedResult = str_replace("\r", "", $expectedResult);

        /** @var string|mixed $result */
        $result = $jsonUtil->extractTotalPerDistinctColumnValue($json, $column);
        $this->assertEquals($expectedResult, $result);
    }

    public function testExtractTotalFromJSON(): void {
        $jsonUtil = JsonUtil::getJsonUtil();

        $json = '[{
"JUDET": "ALBA",
"TOTAL": "1"
}]';
        /** @var int|mixed $expectedResult */
        $expectedResult = 1;

        /** @var int|mixed $result */
        $result = $jsonUtil->extractTotalFromJSON($json);
        $this->assertEquals($expectedResult, $result);
    }

    public function testExtractColumns(): void {
        $jsonUtil = JsonUtil::getJsonUtil();
        $json = '[
{
"JUDET": "ALBA",
"TOTAL": "1"
},
{
"JUDET": "ALBA",
"TOTAL": "1"
}
]';
        $columns = [0];
        $expectedResult = '{
    "JUDET": [
        "ALBA",
        "ALBA"
    ]
}';

        // remove /r from expected result
        /** @var string|mixed $expectedResult */
        $expectedResult = str_replace("\r", "", $expectedResult);

        /** @var string|mixed $result */
        $result = $jsonUtil->extractColumns($json, $columns);
        $this->assertEquals($expectedResult, $result);
    }
}
