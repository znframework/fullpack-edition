<?php namespace ZN;
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
use ZN\Exception\FolderNotFoundException;

class Filesystem
{
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
        $source = Base::suffix($source, '.zip');

        if( ! file_exists($source) )
        {
            return false;
        }

        if( empty($target) )
        {
            $target = self::removeExtension($source);
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
     * Performs data modification on the file.
     * 
     * @param string $file
     * @param mixed  $data
     * @param mixed  $replace
     * 
     * @return string
     */
    public static function replaceData(string $file, $data, $replace) : string
    {
        if( ! is_file($file))
        {
            return false;
        }

        $contents         = file_get_contents($file);
        $replaceContents  = str_ireplace($data, $replace, $contents);

        if( $contents !== $replaceContents )
        {
            file_put_contents($file, $replaceContents);
        }

        return $replaceContents;
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
        if( is_dir($file) )
        {
           return false;
        }

        return mkdir($file, $permission, $recursive);
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
        if( is_dir($name) )
        {
            $name = Base::suffix($name, DS); ;

            foreach( self::getFiles($name) as $val )
            {
                $path = $name . $val;
                
                self::deleteFolder($path);     
            }        
                
            if( ! self::getFiles($name) )
            {
                return self::deleteEmptyFolder($name);
            }       
        }
        else if( is_file($name) )
        {
            return unlink($name);
        }

        return false;
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
        if( ! is_dir($folder) )
        {
           return false;
        }

        return @rmdir($folder);
    }

    /**
     * Creates a file.
     * 
     * @param string $name
     * 
     * @return bool
     */
    public static function createFile(string $name) : bool
    {
        if( ! is_file($name) )
        {
            return touch($name);
        }

        return false;
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
        if( ! file_exists($source) )
        {
            throw new FolderNotFoundException(NULL, $source);
        }

        if( is_dir($source) )
        {
            $source = Base::suffix($source, DS);
            $target = Base::suffix($target, DS);
            
            if( ! is_dir($target) )
            {
                self::createFolder($target);
            }

            if( is_array($getFiles = self::getFiles($source)) )
            {
                foreach( $getFiles as $val )
                {
                    $sourceDir = $source.$val;
                    $targetDir = $target.$val;
    
                    if( is_file($sourceDir) )
                    {
                        copy($sourceDir, $targetDir);
                    }
    
                    self::copy($sourceDir, $targetDir);
                }
            } 

            return true;
        }
        else
        {
            return copy($source, $target);
        }
    }

    /**
     * Get Extension
     * 
     * @param string $file
     * @param bool   $dot = false
     * 
     * @return string
     */
    public static function getExtension(string $file, bool $dot = false) : string
    {
        $dot = $dot === true ? '.' : '';

        return $dot . strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

    /**
     * Remove Extension
     * 
     * @param string $file
     * 
     * @return string
     */
    public static function removeExtension(string $file) : string
    {
        return preg_replace('/\\.[^.\\s]{2,4}$/', '', $file);
    }

    /**
     * Used to retrieve a list of files and directories within a directory.
     * If it is desired to list files with a certain extension between the files to be listed, 
     * the file extension for the second parameter can be used.
     * 
     * @param string $path
     * @param mixed  $extension = NULL
     * @param bool   $pathType  = false
     * 
     * @return array
     */
    public static function getFiles(string $path, $extension = NULL, bool $pathType = false) : array
    {
        if( ! is_dir($path) )
        {
            return [];
        }

        if( is_array($extension) )
        {
            $allFiles = [];

            foreach( $extension as $ext )
            {
                $allFiles = array_merge($allFiles, self::_files($path, $ext, $pathType));
            }

            return $allFiles;
        }

        return self::_files($path, $extension, $pathType);
    }

    /**
     * Used to retrieve a list of all subdirectories and files belonging to a directory. 
     * You can set the second parameter to true 
     * if you want to list the files that are also in the nested indexes.
     * 
     * @param string $pattern  = '*'
     * @param bool   $allFiles = false
     * 
     * @return array
     */
    public static function getRecursiveFiles(string $pattern = '*', bool $allFiles = false, &$getRecursiveFiles = []) : array
    {
        // 5.3.36[added]
        if( $pattern === '/' )
        {
            $pattern = '*';
        }

        if( $allFiles === true )
        {
            if( is_dir($pattern) )
            {
                $pattern = Base::suffix($pattern) . '*';
            }

            $files = glob($pattern);

            if( ! empty($files) ) foreach( $files as $v )
            {
                # Is file
                if( is_file($v) )
                {
                    $getRecursiveFiles[] = $v;
                }
                # Is directory
                elseif( is_dir($v) )
                {
                    # If the directory is empty go to the next step
                    if( ! self::getRecursiveFiles($v, false) )
                    {
                        $getRecursiveFiles[] = $v . DS;

                        continue;
                    }

                    # If the directory is full, continue with directory recursion.
                    self::getRecursiveFiles($v, $allFiles, $getRecursiveFiles); // @codeCoverageIgnore
                }
            }

            return (array) $getRecursiveFiles;
        }

        if( ($cond = preg_match('/.*?\/$/', $pattern)) && strstr($pattern, '*') === false )
        {
            $pattern .= "*";
        }

        if( ! $cond && strstr($pattern, '*') === false )
        {
            $pattern .= "/*"; // @codeCoverageIgnore
        }

        return glob($pattern);
    }

    /**
     * Protected Files
     */
    protected static function _files($path, $extension, $pathType = false)
    {
        $files = [];

        if( empty($path) )
        {
            $path = '.'; // @codeCoverageIgnore
        }

        if( $pathType === true )
        {
            $prefixPath = $path;
        }
        else
        {
            $prefixPath = '';
        }

        $dir = opendir($path);

        while( $file = readdir($dir) )
        {
            if( $file !== '.' && $file !== '..' )
            {
                if( ! empty($extension) && $extension !== 'dir' )
                {
                    if( pathinfo($file, PATHINFO_EXTENSION) === $extension )
                    {
                        $files[] = $prefixPath.$file;
                    }
                }
                else
                {
                    if( $extension === 'dir' )
                    {
                        if( is_dir(Base::suffix($path).$file) )
                        {
                            $files[] = $prefixPath.$file;
                        }
                    }
                    else
                    {
                        $files[] = $prefixPath.$file;
                    }
                }
            }
        }

        return $files;
    }
}
