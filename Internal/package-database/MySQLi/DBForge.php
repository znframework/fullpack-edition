<?php namespace ZN\Database\MySQLi;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Database\DriverForge;

class DBForge extends DriverForge
{
    /**
     * Drop Foreign Key
     * 
     * 5.7.4[added]
     * 
     * @param string $table
     * @param string $constraint = NULL
     * 
     * @return string
     */
    public function dropForeignKey($table, $constraint = NULL)
    {
        return 'ALTER TABLE ' . $table . ' DROP FOREIGN KEY ' . $constraint . ';';
    }
}