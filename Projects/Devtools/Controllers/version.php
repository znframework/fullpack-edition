<?php namespace Project\Controllers;

use Restful;
use Import;
use Redirect;

class Version extends Controller
{
    /**
     * Notes Page
     */
    public function notes(String $params = NULL)
    {
        Import::handload('Functions');
        
        Masterpage::page('versions-notes');
    }
}
