<?php namespace Project\Controllers;

class Home extends Controller
{
    public function main(String $params = NULL)
    {  
        $headers = array(
            'Content-type: application/xml',
            'Connection: 1',
        );
        \CURL::init('Home/test')
        ->cookie('PHPSESSID=1')  
        ->useragent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/65.0.3325.181 Chrome/65.0.3325.181 Safari/537.36')  
        ->httpheader($headers)
        ->exec();
    } 

    public function test()
    {
        output($_SERVER);
    }

    /**
     * Home::s404
     * 
     * Loads show 404 page.
     * Location: Views/Home/s404.wizard.php
     */
    public function s404()
    {
        # Sets masterpage title.
        Masterpage::title($title = '404');

        # Sending data to the view page.
        View::pageTitle($title)->pageSubtitle('The page you searched for was not found!');
    }
}