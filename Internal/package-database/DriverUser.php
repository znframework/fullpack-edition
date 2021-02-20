<?php namespace ZN\Database;
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

class DriverUser
{
    /**
     * Keeps Parameters
     * 
     * @var array
     */
    protected $parameters = [];

    /**
     * Select
     * 
     * @var string
     */
    protected $select;

    /**
     * Type
     * 
     * @var string
     */
    protected $type;

    /**
     * Sets option
     * 
     * @param string $option
     * @param string $value
     */
    public function option($option, $value = NULL)
    {
        if( isset($this->options) && in_array(strtoupper($option), $this->options) )
        {
            $value = Base::presuffix($value, '\'');
        }

        $this->parameters['option'][] = strtoupper($option) . ($value ? ' ' . $value : NULL);
    }

    /**
     * Select
     * 
     * @param string $select = *.*
     */
    public function select($select)
    {
        $this->select = $select;
    }

    /**
     * Type
     * 
     * @param string $type - [TABLE|FUNCTION|PROCEDURE]
     */
    public function type($type)
    {
        $this->type = $type;
    }

    /**
     * protected implode option
     */
    protected function implodeOption()
    {
        return ( ! empty($this->parameters['option']) ? ' WITH ' . implode(' ', $this->parameters['option']) : '' );
    }
}
