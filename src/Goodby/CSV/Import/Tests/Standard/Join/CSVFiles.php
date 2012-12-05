<?php

namespace Goodby\CSV\Import\Tests\Standard\Join;

class CSVFiles
{
    public static function getShiftJisCsv()
    {
        return __DIR__.'/csv_files/sjis.csv';
    }

    public static function getMacExcelCsv()
    {
        return __DIR__.'/csv_files/mac-excel.csv';
    }

    public static function getMacExcelLines()
    {
        return array(
            array('a', 'b', 'c'),
            array('d', 'e', 'f'),
        );
    }
}
