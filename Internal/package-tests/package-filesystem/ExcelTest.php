<?php namespace ZN\Filesystem;

use Excel;
use Buffer;

class ExcelTest extends FilesystemExtends
{
    public function testArrayToXLS()
    {
        $content = Buffer::callback(function()
        {
            Excel::arrayToXLS
            ([
                ['1', '2', '3'],
                ['1', '2', '3']
            ], 'excel');
        });   

        $this->assertIsString($content);
    }

    public function testCSVToArray()
    {
        $this->assertIsArray(Excel::CSVToArray(self::directory . 'test'));
    }
}