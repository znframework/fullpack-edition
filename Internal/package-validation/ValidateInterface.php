<?php namespace ZN\Validation;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface ValidateInterface
{
    /**
     * Get data.
     * 
     * @param string $data
     * 
     * @return Validate
     */
    public function data(string $data) : Validate;

    /**
     * Get result
     * 
     * @param void
     * 
     * @return bool
     */
    public function get() : bool;

    /**
     * Get status.
     * 
     * @param void
     * 
     * @return array
     */
    public function status() : array;
}
