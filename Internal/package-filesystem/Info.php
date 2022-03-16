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

use ZN\Base;
use stdClass;
use ZN\Config;
use ZipArchive;

class Info
{
    /**
     * Access Status
     * 
     * @var bool
     */
    protected static $access = NULL;

    /**
     * Keeps is methods
     * 
     * @var array
     */
    protected static $methods =
    [
        'executable' => 'is_executable',
        'writable'   => 'is_writable',
        'writeable'  => 'is_writeable',
        'readable'   => 'is_readable',
        'uploaded'   => 'is_uploaded_file'
    ];

    /**
     * Magic Call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return bool
     */
    public function __call($method, $parameters)
    {
        return self::_is($method, ...$parameters);
    }

    /**
     * Path Info
     * 
     * @param string $file
     * @param string $info = 'basename'
     * 
     * @return string
     */
    public static function pathInfo(string $file, string $info = 'basename') : string
    {
        $pathInfo = pathinfo($file);

        return $pathInfo[$info] ?? false;
    }

    /**
     * Get required files
     * 
     * @return array
     */
    public static function required() : array
    {
        return get_required_files();
    }

    /**
     * Sets access status
     * 
     * @param bool $realPath              = true
     * @param bool $parentDirectoryAccess = false
     * 
     * @return Info
     */
    public function access($realPath = true, $parentDirectoryAccess = false) : Info
    {
        self::$access['realPath']              = $realPath;
        self::$access['parentDirectoryAccess'] = $parentDirectoryAccess;

        return $this;
    }

    /**
     * Real Path
     * 
     * @param string $file = NULL
     * 
     * @return string
     */
    public static function rpath(string $file = NULL) : string
    {
        $config = Config::get('Filesystem', 'file', self::$access);

        self::$access = NULL;

        if( $config['parentDirectoryAccess'] === false )
        {
            $file = str_replace('../', '', $file);
        }

        if( $config['realPath'] === true )
        {
            $file = Base::prefix(self::originpath($file), self::originpath(REAL_BASE_DIR));
        }

        return $file;
    }

    /**
     * File Exists
     * 
     * @param string $file
     * 
     * @return bool
     */
    public static function exists(string $file) : bool
    {
        $file = self::rpath($file);

        if( is_file($file) )
        {
            return true;
        }

        return false;
    }

    /**
     * Read Zip
     * 
     * @param string $file
     * 
     * @return array
     */
    public static function readZip(string $file) : array
    {
        $file = self::rpath(Base::suffix($file, '.zip'));

        $archive = new ZipArchive($file); 

        $archive->open($file); 

        $returnFiles = [];

        for( $i = 0; $i < $archive->numFiles; $i++ )
        { 
            $stat = $archive->statIndex($i); 

            $returnFiles[] = $stat['name']; 
        }

        return $returnFiles;
    }

    /**
     * Original Path
     * 
     * @param string $string
     * 
     * @return string
     */
    public static function originpath(string $string) : string
    {
        return str_replace(['/', '\\'], DS, $string);
    }

    /**
     * Realtive Path
     * 
     * @param string $string 
     * 
     * @return string
     */
    public static function relativepath(string $string) : string
    {
        return str_replace(REAL_BASE_DIR, '', self::originpath($string));
    }

    /**
     * Available
     * 
     * @param string $file
     * 
     * @return bool
     */
    public static function available(string $file) : bool
    {
        $file = self::rpath($file);

        if( file_exists($file) )
        {
            return true;
        }

        return false;
    }

    /**
     * Get file info
     * 
     * @param string $file
     * 
     * @return object
     */
    public static function get(string $file) : stdClass
    {
        $file = self::rpath($file);

        if( ! file_exists($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        return (object)
        [
            'basename'   => self::pathInfo($file, 'basename'),
            'size'       => filesize($file),
            'date'       => filemtime($file),
            'readable'   => is_readable($file),
            'writable'   => is_writable($file),
            'executable' => is_executable($file),
            'permission' => self::fileperm($file)
        ];
    }

    /**
     * Get file size
     * 
     * @param string $file
     * @param string $type    = 'b'
     * @param int    $decimal = 2
     * 
     * @return float
     */
    public static function size(string $file, string $type = 'b', int $decimal = 2) : Float
    {
        $file = self::rpath($file);

        if( ! file_exists($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        $size      = 0;
        $fileSize  = filesize($file);

        if( is_file($file) )
        {
            $size += $fileSize;
        }
        else
        {
            $folderFiles = FileList::files($file);

            if( $folderFiles )
            {
                foreach( $folderFiles as $val )
                {
                    $size += self::size($file."/".$val);
                }

                $size += $fileSize;
            }
            else
            {
                $size += $fileSize; // @codeCoverageIgnore
            }
        }

        # BYTES
        if( $type === "b" )
        {
            return  $size;
        }
        # KILO BYTES
        if( $type === "kb" )
        {
            return round($size / 1024, $decimal);
        }
        # MEGA BYTES
        if( $type === "mb" )
        {
            return round($size / (1024 * 1024), $decimal);
        }
        # GIGA BYTES
        if( $type === "gb" )
        {
            return round($size / (1024 * 1024 * 1024), $decimal);
        }

        return $size;  // @codeCoverageIgnore
    }

    /**
     * Get create date
     * 
     * @param string $file
     * @param string $type = 'd.m.Y G:i:s'
     * 
     * @return string
     */
    public static function createDate(string $file, string $type = 'd.m.Y G:i:s') : string
    {
        $file = self::rpath($file);

        if( ! file_exists($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        $date = filectime($file);

        return date($type, $date);
    }

    /**
     * Get change date
     * 
     * @param string $file
     * @param string $type = 'd.m.Y G:i:s'
     * 
     * @return string
     */
    public static function changeDate(string $file, string $type = 'd.m.Y G:i:s') : string
    {
        $file = self::rpath($file);

        if( ! file_exists($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        $date = filemtime($file);

        return date($type, $date);
    }

    /**
     * Get file owner
     * 
     * @param string $file
     * 
     * @return array|int
     */
    public static function owner(string $file)
    {
        $file = self::rpath($file);

        if( ! file_exists($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        $owner = fileowner($file);

        if( function_exists('posix_getpwuid') )
        {
            return posix_getpwuid($owner);
        }
        else
        {
            return $owner; // @codeCoverageIgnore
        }
    }

    /**
     * Get file group
     * 
     * @param string $file
     * 
     * @return array|int
     */
    public static function group(string $file)
    {
        $file = self::rpath($file);

        if( ! file_exists($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        $group = filegroup($file);

        if( function_exists('posix_getgrgid') )
        {
            return posix_getgrgid($group);
        }
        else
        {
            return $group; // @codeCoverageIgnore
        }
    }

    /**
     * Gets number of file row
     * 
     * @return string $file      = '/'
     * @param bool    $recursive = true
     * 
     * @return int
     */
    public static function rowCount(string $file = '/', bool $recursive = true) : int
    {
        $file = self::rpath($file);

        if( ! file_exists($file) )
        {
            throw new Exception\FileNotFoundException(NULL, $file);
        }

        if( is_file($file) )
        {
            return count( file($file) );
        }
        # Is dir
        else
        {
            $files = FileList::allFiles($file, $recursive);

            $rowCount = 0;

            foreach( $files as $f )
            {
                if( is_file($f) )
                {
                    $rowCount += count( file($f) );
                }
            }

            return $rowCount;
        }
    }

    /**
     * Get base path
     * 
     * @return string
     */
    public static function basePath() : string
    {
        return getcwd();
    }

    /**
     * Exists Folder
     * 
     * @param string $file
     * 
     * @return bool
     */
    public static function existsFolder(string $file) : bool
    {
        $file = self::rpath($file);

        if( is_dir($file) )
        {
            return true;
        }

        return false;
    }

    /**
     * Used to get various information about a file or directory.
     * 
     * @param string $dir
     * @param string $extension = NULL
     * 
     * @return array
     */
    public static function fileInfo(string $dir, string $extension = NULL) : array
    {
        $dir = self::rpath($dir);

        if( is_dir($dir) )
        {
            $files = FileList::files($dir, $extension);

            $dir = Base::suffix($dir);

            $filesInfo = [];

            foreach( $files as $file )
            {
                $path = $dir . $file;

                $filesInfo[$file]['basename']   = self::pathInfo($path, 'basename');
                $filesInfo[$file]['size']       = filesize($path);
                $filesInfo[$file]['date']       = filemtime($path);
                $filesInfo[$file]['readable']   = is_readable($path);
                $filesInfo[$file]['writable']   = is_writable($path);
                $filesInfo[$file]['executable'] = is_executable($path);
                $filesInfo[$file]['permission'] = self::fileperm($path);
            }

            return $filesInfo;
        }
        elseif( is_file($dir) )
        {
            return (array) self::get($dir);
        }
        else
        {
            throw new Exception\FolderNotFoundException(NULL, $dir);
        }
    }

    /**
     * Get free disk space
     * 
     * @param string $dir 
     * @param string $type = 'free'
     * 
     * @return float
     */
    public static function disk(string $dir, string $type = 'free') : Float
    {
        $dir = self::rpath($dir);

        if( ! is_dir($dir) )
        {
            throw new Exception\FolderNotFoundException(NULL, $dir);
        }

        if( $type === 'free' )
        {
            return disk_free_space($dir);
        }
        else
        {
            return disk_total_space($dir);
        }
    }

    /**
     * Get total disk space
     * 
     * @param string $dir 
     * 
     * @return float
     */
    public static function totalSpace(string $dir) : Float
    {
        return self::disk($dir, 'total');
    }

    /**
     * Get free disk space
     * 
     * @param string $dir 
     * 
     * @return float
     */
    public static function freeSpace(string $dir) : Float
    {
        return self::disk($dir, 'free');
    }

    /**
     * Protected IS
     */
    protected static function _is($type, $file)
    {
        $file = self::rpath($file);

        $validType = self::$methods[$type] ?? NULL;

        if( ! function_exists($validType) || $validType === NULL )
        {
            throw new Exception\UndefinedFunctionException(NULL, get_called_class().'::'.$type.'()'); // @codeCoverageIgnore
        }

        if( $validType($file) )
        {
            return true;
        }

        return false; // @codeCoverageIgnore
    }

    /**
     * Protected file permission
     */
    protected static function fileperm($file)
    {
        return substr(sprintf('%o', fileperms($file)), -4);
    }
}
