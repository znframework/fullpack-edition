<?php namespace Project\Controllers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use Method;
use Redirect;
use Http;
use Generate as GenerateModel;

class Generate extends Controller
{
    /**
     * Generate Controller
     */
    public function controller(String $params = NULL)
    {
        if( Method::post('generate') )
        {
            GenerateModel\Controller::run();
        }

        GenerateModel\Controller::sendMasterpageData();

        Masterpage::page('generate');
    }

    /**
     * Generate Library
     */
    public function library(String $params = NULL)
    {
        if( Method::post('generate') )
        {
            GenerateModel\Library::run();
        }

        GenerateModel\Library::sendMasterpageData();

        Masterpage::page('generate');
    }

    /**
     * Generate Command
     */
    public function command(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        if( Method::post('generate') )
        {
            GenerateModel\Command::run();
        }

        GenerateModel\Command::sendMasterpageData();

        Masterpage::page('generate');
    }

    /**
     * Generate Route
     */
    public function route(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        if( Method::post('generate') )
        {
            GenerateModel\Route::run();
        }

        GenerateModel\Route::sendMasterpageData();

        Masterpage::page('generate');  
    }

    /**
     * Generate Config
     */
    public function config(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        if( Method::post('generate') )
        {
            GenerateModel\Config::run();
        }

        GenerateModel\Config::sendMasterpageData();

        Masterpage::page('generate');
    }

    /**
     * Generate Model
     */
    public function model(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        if( Method::post('generate') )
        {
            GenerateModel\Model::run();
        }

        GenerateModel\Model::sendMasterpageData();

        Masterpage::page('generate');
    }

    /**
     * Generate View
     */
    public function view(String $params = NULL)
    {
        GenerateModel\View::defineTemplates();

        if( Method::post('generate') )
        {
            GenerateModel\View::run();
        }

        GenerateModel\View::sendMasterpageData();

        Masterpage::page('generate');
    }

    /**
     * Generate Starting File
     */
    public function starting(String $params = NULL)
    {
        if( Method::post('generate') )
        {
            GenerateModel\Starting::run();
        }

        GenerateModel\Starting::sendMasterpageData();

        Masterpage::page('generate');
    }

    /**
     * Generate Migration
     */
    public function migration(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        if( Method::post('generate') )
        {
            GenerateModel\Migration::run();
        }

        GenerateModel\Migration::sendMasterpageData();

        Masterpage::page('generate');
    }

    /**
     * Delete File
     */
    public function deleteFile()
    {
        GenerateModel\File::delete();
    }

    /**
     * Ajax Rename File
     */
    public function renameFile()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        GenerateModel\File::rename();
    }

    /**
     * Ajax Save File
     */
    public function saveFile()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        GenerateModel\File::save();
    }
}
