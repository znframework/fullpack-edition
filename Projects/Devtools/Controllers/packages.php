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

use Restful;
use Method;
use Http;
use Processor;
use File;
use Arrays;
use URI;
use Json;
use Folder;
use Config;
use Strings;
use Redirect;

class Packages extends Controller
{
    protected $downloadFileName = FILES_DIR . 'DownloadPackageList.json';

    protected $list = [];

    protected $vendor;

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        parent::__construct();

        if( ! File::exists($this->downloadFileName) )
        {
            File::write($this->downloadFileName, '[]' . EOL);
        }

        $vendor = Config::get('Autoloader', 'composer');

        if( is_bool($vendor) )
        {
            $this->vendor = 'vendor/';
        }
        else
        {
            $this->vendor = rtrim($vendor, 'autoload.php');
        }

        $this->list = Json::decodeArray(File::read($this->downloadFileName));
    }

    /**
     * Main Page
     */
    public function main(String $params = NULL)
    {
        if( Method::post('search') )
        {
            $data = Restful::get('https://packagist.org/search.json?q=' . Method::post('name') );

            $pdata['result'] = $data->results;
         }

        $pdata['list'] = $this->list;

        Masterpage::page('package');

        Masterpage::pdata($pdata);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $newList = Arrays::deleteElement($this->list, $packageName = URI::get('delete', 2, true));

        $deletePackageName = $this->vendor . Strings::divide($packageName, '/');

        if( Folder::exists($deletePackageName) )
        {
            Folder::delete($deletePackageName);
        }

        File::write($this->downloadFileName, Json::encode($newList) . EOL);

        Redirect::location('packages');
    }

    /**
     * Ajax Download
     */
    public function download()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        $name = Method::post('name');

        exec('composer require ' . $name, $response, $return);

        if( $return == 0 )
        {
            $data = Json::decodeArray(File::read($this->downloadFileName));

            $data = Arrays::addLast($data, $name);

            File::write($this->downloadFileName, Json::encode($data) . EOL);
        }

        echo $return;

        exit;
    }
}
