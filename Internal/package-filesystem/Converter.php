<?php namespace ZN\Filesystem;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Base;

class Converter
{
    /**
     * Array to XLS
     * 
     * @param array  $data
     * @param string $file    = 'excel.xls'
     * @param bool   $useBoom = false
     */
    public static function arrayToXLS(array $data, string $file = 'excel', bool $useBom = false, string $extension = '.xls')
    {
        $file = Base::suffix($file, $extension);

        header("Content-Disposition: attachment; filename=\"$file\"");
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", 'w');

        if( $useBom === true )
        {
            echo "\xEF\xBB\xBF";
        }
        
        foreach( $data as $column )
        {
            echo implode($extension === '.csv' ? ';' : '\t', $column) . EOL;
        }
    }

    /**
     * Array to CSV
     * 
     * @param array  $data
     * @param string $file    = 'excel.csv'
     * @param bool   $useBoom = false
     */
    public static function arrayToCSV(array $data, string $file = 'excel', bool $useBom = false)
    {
        self::arrayToXLS($data, $file, $useBom, '.csv');
    }

    /**
     * CSV to Array
     * 
     * @param string $file
     * 
     * @return array
     */
    public static function CSVToArray(string $file) : array
    {
        $file = Base::suffix($file, '.csv');

        if( ! is_file($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file); // @codeCoverageIgnore
        }

        $row  = 1;
        $rows = [];

        if( ( $resource = fopen($file, "r") ) !== false )
        {
            while( ($data = fgetcsv($resource, 1000, ",")) !== false )
            {
                $num = count($data);

                $row++;
                
                for( $c = 0; $c < $num; $c++ )
                {
                    $rows[] = explode(';', $data[$c]);
                }
            }

            fclose($resource);
         }

         return $rows;
    }
}
