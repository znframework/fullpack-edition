<?php namespace ZN\Tests;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Controller\UnitTest;

class IS extends UnitTest
{
    public function _software()
    {
        $this->compare('apache', $this->software());
    }

    public function _timeZone()
    {
        $this->compare(true, $this->timeZone('Europe/Istanbul'));
    }

    public function _phpVersion()
    {
        $this->compare(true, $this->phpVersion('7.0.0'));
    }

    public function _phpVersion2()
    {
        $this->compare(false, $this->phpVersion('8.0.0'));
    }

    public function _import()
    {
        $this->compare(true, $this->import('zeroneed'));
    }

    public function _url()
    {
        $this->compare(true, $this->url('http://www.znframework.com'));
    }

    public function _url2()
    {
        $this->compare(false, $this->url('www.znframework.com'));
    }

    public function _email()
    {
        $this->compare(true, $this->email('robot@znframework.com'));
    }

    public function _email2()
    {
        $this->compare(false, $this->email('robot@znframework'));
    }

    public function _char()
    {
        $this->compare(true, $this->char('robot@znframework'));
    }

    public function _realNumeric()
    {
        $this->compare(false, $this->realNumeric('10'));
    }

    public function _realNumeric2()
    {
        $this->compare(true, $this->realNumeric(10));
    }

    public function _declaredClass()
    {
        $this->compare(false, $this->declaredClass('ZN\Storage\Session'));
    }

    public function _hash()
    {
        $this->compare(true, $this->hash('md5'));
    }

    public function _charset()
    {
        $this->compare(true, $this->charset('utf-8'));
    }

    public function _array()
    {
        $this->compare(true, $this->array(['a']));
    }

    public function _array2()
    {
        $this->compare(false, $this->array([]));
    }
}
