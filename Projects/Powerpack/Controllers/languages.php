<?php namespace Project\Controllers;

use MLGrid;

class Languages extends Controller
{
    public function main()
    {
        View::pageTitle('Languages')->pageBody
        (
            MLGrid::create()
        );
    }
}
