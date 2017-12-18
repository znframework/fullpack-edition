<?php namespace ZN\ViewObjects;

class SheetUnitTest extends \UnitTestController
{
    //--------------------------------------------------------------------------------------------------------
    //
    // Author     : Ozan UYKUN <ozanbote@gmail.com>
    // Site       : www.znframework.com
    // License    : The MIT License
    // Copyright  : (c) 2012-2016, znframework.com
    //
    //--------------------------------------------------------------------------------------------------------

    const unit =
    [
        'class'   => 'Sheet',
        'methods' => 
        [
            'selector'          => ['#color'],
            'attr'              => [['color' => 'red']],
            #'complete'         => [],
            'create'            => []
        ]
    ];
}