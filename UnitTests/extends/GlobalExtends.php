<?php namespace ZN\Test;

class GlobalExtends extends \PHPUnit\Framework\TestCase
{
    const default = 'UnitTests/';

    public function __construct()
    {
        parent::__construct();

        define('ZN_REDIRECT_NOEXIT', true);
    }
}