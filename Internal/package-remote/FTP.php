<?php namespace ZN\Remote;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Config;
use ZN\Helper;
use ZN\Filesystem;
use ZN\Remote\Exception\IOException;
use ZN\Remote\FTPDefaultConfiguration;
use ZN\Remote\Exception\FolderAllreadyException;
use ZN\Remote\Exception\FolderNotFoundException;
use ZN\Remote\Exception\FolderChangeDirException;
use ZN\Remote\Exception\FolderChangeNameException;
use ZN\Remote\Exception\FileRemoteUploadException;
use ZN\Remote\Exception\FileRemoteDownloadException;


class FTP implements FTPInterface, RemoteInterface
{
    /**
     * Connect
     * 
     * @var resource
     */
    protected $connect = NULL;

    /**
     * Login
     * 
     * @var resource
     */
    protected $login = NULL;

    /**
     * Magic Constructor
     * 
     * @param array $config = []
     */
    public function __construct(Array $config = [])
    {
        if( ! empty($config) )
        {
            $config = Config::get('Services', 'ftp', $config);
        }
        else
        {
            $config = Config::default('ZN\Remote\FTPDefaultConfiguration')
                            ::get('Services', 'ftp');
        }

        $this->_connect($config);
    }

    /**
     * Magic Destructor
     */
    public function __destruct()
    {
        $this->_close();
    }

    /**
     * Create Folder
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function createFolder(String $path) : Bool
    {
        if( ftp_mkdir($this->connect, $path) )
        {
            return true;
        }
        else
        {
            throw new FolderAllreadyException(NULL, $path);
        }
    }

    /**
     * Delete Folder
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function deleteFolder(String $path) : Bool
    {
        if( ftp_rmdir($this->connect, $path) )
        {
            return true;
        }
        else
        {
            throw new FolderNotFoundException(NULL, $path);
        }
    }

    /**
     * Change Folder
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function changeFolder(String $path) : Bool
    {
        if( ftp_chdir($this->connect, $path) )
        {
            return true;
        }
        else
        {
            throw new FolderChangeDirException(NULL, $path);
        }
    }

    /**
     * Rename
     * 
     * @param string $oldName
     * @param string $newName
     * 
     * @return bool
     */
    public function rename(String $oldName, String $newName) : Bool
    {
        if( ftp_rename($this->connect, $oldName, $newName) )
        {
            return true;
        }
        else
        {
            throw new FolderChangeNameException(NULL, $oldName);
        }
    }

    /**
     * Delete File
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function deleteFile(String $path) : Bool
    {
        if( ftp_delete($this->connect, $path) )
        {
            return true;
        }
        else
        {
            throw new FileNotFoundException(NULL, $path);
        }
    }

    /**
     * Permission
     * 
     * @param string $path
     * @param int    $type = 0755
     * 
     * @return bool
     */
    public function permission(String $path, Int $type = 0755) : Bool
    {
        if( ftp_chmod($this->connect, $type, $path) )
        {
            return true;
        }
        else
        {
            throw new IOException(NULL, '$this->connect');
        }
    }

    /**
     * Get Files
     * 
     * @param string $path
     * @param string $extension = NULL
     * 
     * @return array
     */
    public function files(String $path, String $extension = NULL) : Array
    {
        $list = ftp_nlist($this->connect, $path);

        if( ! empty($list) ) foreach( $list as $file )
        {
            if( $file !== '.' && $file !== '..' )
            {
                if( ! empty($extension) && $extension !== 'dir' )
                {
                    if( Filesystem::getExtension($file) === $extension )
                    {
                        $files[] = $file;
                    }
                }
                else
                {
                    if( $extension === 'dir' )
                    {
                        $extens = Filesystem::getExtension($file);

                        if( empty($extens) )
                        {
                            $files[] = $file;
                        }
                    }
                    else
                    {
                        $files[] = $file;
                    }
                }
            }
        }

        if( ! empty($files) )
        {
            return $files;
        }
        else
        {
            return [];
        }
    }

    /**
     * Get File Size
     * 
     * @param string $path
     * @param string $type = 'b' - options[b|kb|mb|gb]
     * 
     * @return float
     */
    public function fileSize(String $path, String $type = 'b', Int $decimal = 2) : Float
    {
        $size = 0;

        $extension = Filesystem::getExtension($path);

        if( ! empty($extension) )
        {
            $size = ftp_size($this->connect, $path);
        }
        else
        {
            if( $this->files($path) )
            {
                foreach( $this->files($path) as $val )
                {
                    $size += ftp_size($this->connect, $path."/".$val);
                }

                $size += ftp_size($this->connect, $path);
            }
            else
            {
                $size += ftp_size($this->connect, $path);
            }
        }

        switch( $type )
        {
            case 'b' : return $size;
            case 'kb': return round($size / 1024, $decimal);
            case 'mb': return round($size / (1024 * 1024), $decimal);
            case 'gb': return round($size / (1024 * 1024 * 1024), $decimal);
        }
    }

    /**
     * File Upload
     * 
     * @param string $localPath
     * @param string $remotePath
     * @param string $type = 'ascii'
     * 
     * @return bool
     */
    public function upload(String $localPath, String $remotePath, String $type = 'ascii') : Bool
    {
        if( ftp_put($this->connect, $remotePath, $localPath, Helper::toConstant($type, 'FTP_')) )
        {
            return true;
        }
        else
        {
            throw new FileRemoteUploadException($localPath);
        }
    }

    /**
     * File Download
     * 
     * @param string $remotePath
     * @param string $localPath
     * @param string $type = 'ascii'
     * 
     * @return bool
     */
    public function download(String $remotePath, String $localPath, String $type = 'ascii') : Bool
    {
        if( ftp_get($this->connect, $localPath, $remotePath, Helper::toConstant($type, 'FTP_')) )
        {
            return true;
        }
        else
        {
            throw new FileRemoteDownloadException($localPath);
        }
    }

    /**
     * Protected Connect
     */
    protected function _connect($config)
    {
        # Connect Configuration
        $host     = $config['host'];
        $port     = $config['port'];
        $timeout  = $config['timeout'];
        $user     = $config['user'];
        $password = $config['password'];
        $ssl      = $config['sslConnect'];
 
        $this->connect = $ssl === false
                       ? ftp_connect($host, $port, $timeout)
                       : ftp_ssl_connect($host, $port, $timeout);

        if( empty($this->connect) )
        {
            throw new IOException('Error', 'emptyVariable', 'Connect');
        }

        $this->login = ftp_login($this->connect, $user, $password);

        if( empty($this->login) )
        {
            throw new IOException('Error', 'emptyVariable', 'Login');
        }
    }

    /**
     * Protected Close
     */
    protected function _close()
    {
        if( ! empty($this->connect) )
        {
            return ftp_close($this->connect);
        }
    }

    /**
     * Different Connection
     * 
     * @param array $config
     * 
     * @return Connection
     */
    public function differentConnection(Array $config) : FTP
    {
        return new self($config);
    }
}
