<?php namespace ZN\Database\Postgres;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Database\DriverTrigger;

class DBTrigger extends DriverTrigger
{
   /**
     * Body
     * 
     * @param string ...$args 
     * 
     * BEGIN $arg1; $arg2; .... $arg3; END;
     */
    public function body(...$args)
    {
        if( is_array($args[0]) )
        {
            $args = $args[0];
        }

        $this->body = 'EXECUTE PROCEDURE '.implode('; ', $args).';';
    }

    /**
     * Drop Trigger
     * 
     * @param string $name
     * 
     * @return string
     */
    public function dropTrigger($name, $type = NULL)
    {
        return 'DROP TRIGGER ON '.$name. ($type ? ' ' . $type : NULL) . ';';
    }
}