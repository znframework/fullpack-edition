<?php namespace ZN\DataTypes;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface VarsInterface
{
    //--------------------------------------------------------------------------------------------------------
    // Bool
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //--------------------------------------------------------------------------------------------------------
    public function bool($var) : Bool;

    //--------------------------------------------------------------------------------------------------------
    // Boolean
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //--------------------------------------------------------------------------------------------------------
    public function boolean($var) : Bool;

    //--------------------------------------------------------------------------------------------------------
    // Float
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //--------------------------------------------------------------------------------------------------------
    public function float($var) : Float;

    //--------------------------------------------------------------------------------------------------------
    // Double
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //--------------------------------------------------------------------------------------------------------
    public function double($var) : Float;

    //--------------------------------------------------------------------------------------------------------
    // Int
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //--------------------------------------------------------------------------------------------------------
    public function int($var) : Int;

    //--------------------------------------------------------------------------------------------------------
    // Integer
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //--------------------------------------------------------------------------------------------------------
    public function integer($var) : int;

    //--------------------------------------------------------------------------------------------------------
    // String
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //--------------------------------------------------------------------------------------------------------
    public function string($var) : String;

    //--------------------------------------------------------------------------------------------------------
    // Type
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //--------------------------------------------------------------------------------------------------------
    public function type($var) : String;

    //--------------------------------------------------------------------------------------------------------
    // Resource Type
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $resource
    //
    //--------------------------------------------------------------------------------------------------------
    public function resourceType($resource) : String;

    //--------------------------------------------------------------------------------------------------------
    // Serial
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //-------------------------------------------------------------------------------------------------------
    public function serial($var) : String;

    //--------------------------------------------------------------------------------------------------------
    // Unserial
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //-------------------------------------------------------------------------------------------------------
    public function unserial(String $var);

    //--------------------------------------------------------------------------------------------------------
    // Remove
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //-------------------------------------------------------------------------------------------------------
    public function remove($var);

    //--------------------------------------------------------------------------------------------------------
    // Delete
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //-------------------------------------------------------------------------------------------------------
    public function delete($var);

    //--------------------------------------------------------------------------------------------------------
    // To Type
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $var
    //
    //-------------------------------------------------------------------------------------------------------
    public function toType($var, String $type = 'integer');
}
