<?php namespace ZN\Services;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

/**
 * Default Configuration
 * 
 * Enabled when the configuration file can not be accessed.
 */
class CDNDefaultConfiguration
{
   /*
    |--------------------------------------------------------------------------
    | Scripts
    |--------------------------------------------------------------------------
    |
    | CDN Javascript links.
    |
    | It can be used with the Import::script() method.
    |
    | Example: Import::script('jquery', 'react');
    |
    */

    public $scripts =
    [
        'jquery'          => 'https://code.jquery.com/jquery-latest.js',
        'bootstrap'       => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
        'angular'         => 'https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js',
        'react'           => 'https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react.min.js',
        'vue'             => 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.3/vue.min.js',
    ];

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    |
    | CDN CSS links.
    |
    | It can be used with the Import::style() method.
    |
    | Example: Import::style('bootstrap', 'awesome');
    |
    */

    public $styles =
    [
        'bootstrap'  => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
        'awesome'    => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
    ];

    /*
    |--------------------------------------------------------------------------
    | Fonts
    |--------------------------------------------------------------------------
    |
    | CDN Font links.
    |
    | It can be used with the CDN::font() method.
    |
    | Example: CDN::font('robotic');
    |
    */

    public $fonts = [];

    /*
    |--------------------------------------------------------------------------
    | Images
    |--------------------------------------------------------------------------
    |
    | CDN Image links.
    |
    | It can be used with the CDN::image() method.
    |
    | Example: CDN::image('wallpaper');
    |
    */

    public $images = [];

    /*
    |--------------------------------------------------------------------------
    | Files
    |--------------------------------------------------------------------------
    |
    | CDN Files links.
    |
    | It can be used with the CDN::file() method.
    |
    | Example: CDN::file('note');
    |
    */

    public $files = [];
}
