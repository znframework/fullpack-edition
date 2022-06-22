<?php namespace ZN\Filesystem;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZipArchive;
use ZN\Base;
use ZN\Regex;
use ZN\Filesystem;

class Forge
{
    /**
     * Creates a file.
     * 
     * @param string $name
     * 
     * @return bool
     */
    public static function create(string $name) : bool
    {
        return Filesystem::createFile(Info::rpath($name));
    }

    /**
     * Performs data modification on the file.
     * 
     * @param string $file
     * @param mixed  $data
     * @param mixed  $replace
     * 
     * @return string
     */
    public static function replace(string $file, $data, $replace) : string
    {
        $file = Info::rpath($file);

        if( ! is_file($file))
        {
            return false;
        }

        return Filesystem::replaceData($file, $data, $replace);
    }

    /**
     * Performs data modification on the file with ZN regex.
     * 
     * @param string $file
     * @param mixed  $pattern
     * @param mixed  $replace
     * 
     * @return string
     */
    public static function reglace(string $file, $pattern, $replace) : string
    {
        $file = Info::rpath($file);

        if( ! is_file($file))
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        $contents        = file_get_contents($file);
        $replaceContents = (new Regex)->replace($pattern, $replace, $contents);

        if( $contents !== $replaceContents )
        {
            file_put_contents($file, $replaceContents);
        }

        return $replaceContents;
    }

    /**
     * Deletes the file.
     * 
     * @param string $name
     * 
     * @return bool
     */
    public static function delete(string $name) : bool
    {
        $name = Info::rpath($name);

        if( ! is_file($name))
        {
            throw new Exception\FileNotFoundException(NULL, $name);
        }
        else
        {
            return unlink($name);
        }
    }

    /**
     * Extracts a zip file to the specified location.
     * 
     * @param string $source
     * @param string $target
     * 
     * @return bool
     */
    public static function zipExtract(string $source, string $target = NULL) : bool
    {
        $source = Info::rpath($source);
        $target = Info::rpath($target);

        $source = Base::suffix($source, '.zip');

        if( ! file_exists($source) )
        {
            throw new Exception\FileNotFoundException(NULL, $source);
        }

        if( empty($target) )
        {
            $target = Extension::remove($source);
        }

        $zip = new ZipArchive;

        if( $zip->open($source) === true )
        {
            $zip->extractTo($target);
            $zip->close();

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Create a zip file.
     * 
     * @param string $path
     * @param array  $data
     * 
     * @return bool
     */
    public static function createZip(string $path, array $data) : bool
    {
        $opath   = $path;
        $path    = Info::rpath($path);
        $zip     = new ZipArchive;
        $zipPath = Base::suffix($path, ".zip");

        if( file_exists($zipPath) )
        {
            unlink($zipPath); // @codeCoverageIgnore
        }

        if( ! is_dir($pathDirName = Info::pathInfo($path, 'dirname')) )
        {
            mkdir($pathDirName);
        }

        $zip->open($zipPath, ZipArchive::CREATE);

        $status = '';

        if( ! empty($data) ) foreach( $data as $key => $val )
        {
            if( is_numeric($key) )
            {
                $file = $val;
                $fileName = '';
            }
            else
            {
                $file = $key;
                $fileName = $val;
            }

            if( is_dir($file) )
            {
                $allFiles = FileList::allFiles($file, true);

                foreach( $allFiles as $f )
                {
                    $status = $zip->addFile($f, Base::removePrefix($f, Base::suffix($opath)));
                }
            }
            else
            {
                $status = $zip->addFile($file, $fileName);
            }
        }

        return $zip->close();
    }

    /**
     * Change the name of a file.
     * 
     * @param string $oldName
     * @param string $newName
     * 
     * @return bool
     */
    public static function rename(string $oldName, string $newName) : bool
    {
        $oldName = Info::rpath($oldName);

        if( ! file_exists($oldName) )
        {
            throw new Exception\FileNotFoundException(NULL, $oldName);
        }

        return rename($oldName, $newName);
    }

    /**
     * Clears file status cache
     * 
     * @param string $fileName = NULL
     * @param bool   $real     = false
     */
    public static function cleanCache(string $fileName = NULL, bool $real = false)
    {
        $fileName = Info::rpath($fileName);

        if( ! file_exists($fileName) )
        {
            clearstatcache($real);
        }
        else
        {
            clearstatcache($real, $fileName);
        }
    }

    /**
     * Truncates a file to a given length
     * 
     * @param string $file
     * @param int    $limit = 0
     * @param string $mode  = 'r+'
     */
    public static function truncate(string $file, int $limit = 0, string $mode = 'r+')
    {
        $file = Info::rpath($file);

        if( ! is_file($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        $fileOpen  = fopen($file, $mode);
        $fileWrite = ftruncate($fileOpen, $limit);

        fclose($fileOpen);
    }

    /**
     * Changes file mode.
     * 
     * @param string $name
     * @param int    $permission = 0755
     * 
     * @return bool
     */
    public static function permission(string $name, int $permission = 0755) : bool
    {
        $name = Info::rpath($name);

        if( ! file_exists($name) )
        {
            throw new Exception\FileNotFoundException(NULL, $name);
        }

        return chmod($name, $permission);
    }

    /**
     * Create an index.
     * 
     * @param string $file
     * @param int    $permission = 0755
     * @param bool   $recursive  = true
     * 
     * @return bool
     */
    public static function createFolder(string $file, int $permission = 0755, bool $recursive = true) : bool
    {
        return Filesystem::createFolder(Info::rpath($file), $permission, $recursive);
    }

    /**
     * Used to delete an empty directory.
     * 
     * @param string $folder
     * 
     * @return bool
     */
    public static function deleteEmptyFolder(string $folder) : bool
    {
        return Filesystem::deleteEmptyFolder(Info::rpath($folder));
    }

    /**
     * Used to delete a directory along with its contents.
     * 
     * @param string $name
     * 
     * @return bool
     */
    public static function deleteFolder(string $name) : bool
    {
        return Filesystem::deleteFolder(Info::rpath($name));
    }

    /**
     * Used to copy a directory to another specified location. 
     * This includes other subdirectories and files of the directory to be copied.
     * 
     * @param string $source
     * @param string $target
     * 
     * @return bool
     */
    public static function copy(string $source, string $target) : bool
    {
        return Filesystem::copy(Info::rpath($source), Info::rpath($target));
    }

    /**
     * It is used to change the active working directory of PHP.
     * 
     * @param string $name
     * 
     * @return bool
     */
    public static function changeFolder(string $name) : bool
    {
        $name = Info::rpath($name);

        if( ! is_dir($name) )
        {
            throw new Exception\FolderNotFoundException(NULL, $name);
        }

        return chdir($name);
    }
    
}
