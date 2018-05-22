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

use DB;
use DBTool;
use DBForge;
use Import;
use Session; 
use Config;
use Folder;
use File;
use Http;
use Method;
use ZN\DataTypes\Arrays\RemoveElement;
use ZN\Security\Html;
use ZN\Base;
use Datatables as DatatablesModel;

class Datatables extends Controller
{
    /**
     * Main
     */
    public function main(String $params = NULL)
    {
        # Sending data to Masterpage.
        Masterpage::pdata(['tables' => DBTool::listTables()]);

        # The corresponding view is being loaded.
        Masterpage::page('datatable');
    }

    /**
     * Ajax Create New Table
     */
    public function createNewTable()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Table::create();
    } 

    /**
     * Ajax Alter Table
     */
    public function alterTable()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Table::alter();
    }

    /**
     * Ajax Drop Table
     */
    public function dropTable()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Table::drop();
    }

    /**
     * Ajax Update Rows
     */
    public function updateRows()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Row::updateAll();
    }

    /**
     * Ajax Update Row
     */
    public function updateRow()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Row::update();
    }

    /**
     * Ajax Add Row
     */
    public function addRow()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Row::add();
    }
    
    /**
     * Ajax Delete Row
     */
    public function deleteRow()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Row::delete();
    }

    /**
     * Ajax Pagination Row
     */
    public function paginationRow()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Row::pagination();
    }

    /**
     * Ajax Drop Column
     */
    public function dropColumn()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Column::drop();
    }  

    /**
     * Ajax Modify Column
     */
    public function modifyColumn()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        DatatablesModel\Column::modify();
    }
}
