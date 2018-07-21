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
use Initialize\Menus;
use Generate as GenerateModel;

class Generate extends Controller
{
    /**
     * Generate Controller
     */
    public function controller(String $params = NULL)
    {
        if( ! Menus::isDirectory('Controllers') )
        {
            Redirect::location();
        }

        # Generating controller.
        if( Method::post('generate') )
        {
            GenerateModel\Controller::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\Controller::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');
    }

    /**
     * Generate Library
     */
    public function library(String $params = NULL)
    {
        if( ! Menus::isDirectory('Libraries') )
        {
            Redirect::location();
        }

        # Generating library.
        if( Method::post('generate') )
        {
            GenerateModel\Library::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\Library::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');
    }

    /**
     * Generate Command
     */
    public function command(String $params = NULL)
    {
        if( ! Menus::isDirectory('Commands') )
        {
            Redirect::location();
        } 

        # Generating command.
        if( Method::post('generate') )
        {
            GenerateModel\Command::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\Command::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');
    }

    /**
     * Generate Route
     */
    public function route(String $params = NULL)
    {
        if( ! Menus::isDirectory('Routes') )
        {
            Redirect::location();
        }

        # Generating route.
        if( Method::post('generate') )
        {
            GenerateModel\Route::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\Route::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');  
    }

    /**
     * Generate Config
     */
    public function config(String $params = NULL)
    {
        if( ! Menus::isDirectory('Config') )
        {
            Redirect::location();
        }

        # Generating config.
        if( Method::post('generate') )
        {
            GenerateModel\Config::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\Config::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');
    }

    /**
     * Generate Model
     */
    public function model(String $params = NULL)
    {
        if( ! Menus::isDirectory('Models') )
        {
            Redirect::location();
        }

        # Generating model.
        if( Method::post('generate') )
        {
            GenerateModel\Model::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\Model::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');
    }

    /**
     * Generate View
     */
    public function view(String $params = NULL)
    {
        if( ! Menus::isDirectory('Views') )
        {
            Redirect::location();
        }

        GenerateModel\View::defineTemplates();

        # Generating view.
        if( Method::post('generate') )
        {
            GenerateModel\View::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\View::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');
    }

    /**
     * Generate Starting File
     */
    public function starting(String $params = NULL)
    {   
        if( ! Menus::isDirectory('Starting') )
        {
            Redirect::location();
        }

        # Generating starting file.
        if( Method::post('generate') )
        {
            GenerateModel\Starting::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\Starting::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');
    }

    /**
     * Generate Migration
     */
    public function migration(String $params = NULL)
    {
        if( ! Menus::isDirectory('Models') )
        {
            Redirect::location();
        }

        # Generating migration.
        if( Method::post('generate') )
        {
            GenerateModel\Migration::run();
        }

        # The required data is being sent for the masterpage.
        GenerateModel\Migration::sendMasterpageData();

        # The corresponding view is being loaded.
        Masterpage::page('generate');
    }

    /**
     * Delete File
     */
    public function deleteFile()
    {
        # Deleting file.
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

        # The file is being renamed.
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

        # The changes are being saved.
        GenerateModel\File::save();
    }
}
