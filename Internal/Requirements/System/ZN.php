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

use ZN\Core\Kernel;
use ZN\Services\URI;
use ZN\FileSystem\File;
use ZN\FileSystem\Folder;
use ZN\Helpers\Converter;
use ZN\DataTypes\Separator;
use ZN\IndividualStructures\Lang;
use ZN\IndividualStructures\Buffer;
use ZN\ErrorHandling\Exceptions;

class ZN
{
    /**
     * Use library
     * 
     * @var mixed
     */
    public static $use;

    /**
     * Get ZN version
     * 
     * @var string
     */
    const VERSION = ZN_VERSION;

    /**
     * Get required php version
     * 
     * @var string
     */
    const REQUIRED_PHP_VERSION = REQUIRED_PHP_VERSION;

    /**
     * Upgrade system
     * 
     * @param void
     * 
     * @return bool
     */
    public static function upgrade()
    {
        $return = self::_restful();

        if( ! empty($return) )
        {
            $upgradeFolder = 'Upgrade'.md5('upgrade').'/';

            \Folder::create($upgradeFolder);

            foreach( $return as $file => $content )
            {
                $file = $upgradeFolder . $file;

                $dirname = \File::pathInfo($file, 'dirname');

                \Folder::create($dirname); 
                
                if( ! empty($content) )
                {
                    \File::write($file, $content);
                } 
            }

            \Folder::copy($upgradeFolder, '/'); \Folder::delete($upgradeFolder);

            return true;
        }

        return false;
    }

    /**
     * Get upgrade files
     * 
     * @param void
     * 
     * @return array
     */
    public static function upgradeFiles()
    {
        return array_keys(self::_restful());
    }

    /**
     * Run ZN
     * 
     * @param void
     * 
     * @return void
     */
    public static function run()
    {
        \Route::filter();

        $projectConfig = \Config::get('Project', 'cache');

        if
        (
            ($projectConfig['status'] ?? NULL) === true                                                         &&
            ( ! in_array(\User::ip(), ($projectConfig['machinesIP'] ?? [])) )                                   &&
            ( empty($projectConfig['include']) || in_array(CURRENT_CFPATH, ($projectConfig['include'] ?? [])) ) &&
            ( empty($projectConfig['exclude']) || ! in_array(CURRENT_CFPATH, ($projectConfig['exclude'] ?? [])) )
        )
        {
            $converterName = Converter::slug(URI::active());

            $cacheName = ($projectConfig['prefix'] ?? Lang::get()) . '-' . $converterName;

            \Cache::driver($projectConfig['driver']);

            if( ! $select = \Cache::select($cacheName, $projectConfig['compress']) )
            {
                $kernel = Buffer\Callback::do(function()
                {
                    Kernel::run();
                });

                \Cache::insert($cacheName, $kernel, $projectConfig['time'], $projectConfig['compress']);

                echo $kernel;
            }
            else
            {
                echo $select;
            }
        }
        else
        {
            try 
            { 
                Kernel::run();  
            }
            catch( \Throwable $e )
            {
                if( PROJECT_MODE !== 'publication' ) 
                {
                    Exceptions::table($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTrace());
                }   
            }
        } 
    }

    /**
     * Magic call static
     * 
     * @param string $class
     * @param array  $parameters
     * 
     * @return mixed
     */
    public static function __callStatic($class, $parameters)
    {
        return uselib($class, $parameters);
    }

    /**
     * protected restful
     * 
     * @param void
     * 
     * @return array
     */
    protected static function _restful()
    {
        $return  = \Restful::useragent(true)->get('https://api.github.com/repos/znframework/fullpack-edition/tags');
        $lastest = $return[0];

        $updatedFiles = [];
        
        if( ZN_VERSION < $lastest->name ) foreach( $return as $version )
        {
            if( ZN_VERSION < $version->name )
            {
                $commit = \Restful::useragent(true)->get($version->commit->url);

                foreach( $commit->files as $file )
                {
                    if( ! isset($updatedFiles[$file->filename]) )
                    {
                        $updatedFiles[$file->filename] = file_get_contents($file->raw_url);
                    }        
                }
            }
            else
            {
                break;
            }
        }
        
        return $updatedFiles;
    }

    /**
     * protected spath
     * 
     * @param string $path
     * 
     * @return string
     */
    protected static function _spath($path)
    {
        return str_replace(['Internal/', 'External/', 'Settings/'], ['Libraries/', NULL, 'Config/'], $path);
    }
}

# Alias ZN
class_alias('Project\Controllers\ZN', 'ZN');
