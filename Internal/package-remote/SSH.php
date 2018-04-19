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
use ZN\Support;
use ZN\Remote\Exception\FileNotFoundException;
use ZN\Remote\Exception\FileRemoteUploadException;
use ZN\Remote\Exception\FileRemoteDownloadException;
use ZN\Remote\Exception\FolderChangeNameException;
use ZN\Remote\Exception\FolderNotFoundException;
use ZN\Remote\Exception\FolderAllreadyException;
use ZN\Remote\Exception\InvalidArgumentException;

class SSH implements SSHInterface, RemoteInterface
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
     * Stream
     * 
     * @var resource
     */
    protected $stream = NULL;

    /**
     * Command
     * 
     * @var string
     */
    protected $command = '';

    /**
     * Magic Constructor
     * 
     * @param array $config = []
     */
    public function __construct(Array $config = [])
    {
        Support::func('ssh2_connect', 'SSH(Secure Shell)');

        if( ! empty($config) )
        {
            $config = Config::get('Services', 'ssh', $config);
        }
        else
        {
            $config = Config::default('ZN\Remote\SSHDefaultConfiguration')
                            ::get('Services', 'ssh');
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
     * Command
     * 
     * @param string $command
     * 
     * @return SSH
     */
    public function command(String $command) : SSH
    {
        $this->command .= $command.' ';

        return $this;
    }

    /**
     * Run
     * 
     * @param string $command = NULL
     * 
     * @return resource|false
     */
    public function run(String $command = NULL)
    {
        if( ! empty($this->connect) )
        {
            if( ! empty($this->command) )
            {
                $command = rtrim($this->command);
            }

            $this->_defaultVariables();

            return $this->stream = ssh2_exec($this->connect, $command);
        }

        return false;
    }

    /**
     * Output
     * 
     * @param int $length = 4096
     * 
     * @return string
     */
    public function output(Int $length = 4096) : String
    {
        $stream = $this->stream;

        stream_set_blocking($stream, true);

        $data = "";

        while( $buffer = fread($stream, $length) )
        {
            $data .= $buffer;
        }

        fclose($stream);

        return $data;
    }

    /**
     * File Upload
     * 
     * @param string $localPath
     * @param string $remotePath
     * 
     * @return bool
     */
    public function upload(String $localPath, String $remotePath) : Bool
    {
        if( @ssh2_scp_send($this->connect, $localPath, $remotePath) )
        {
            return true;
        }
        else
        {
            throw new FileRemoteUploadException(NULL, $localPath);
        }
    }

    /**
     * File Download
     * 
     * @param string $remotePath
     * @param string $localPath
     * 
     * @return bool
     */
    public function download(String $remotePath, String $localPath) : Bool
    {
        if( @ssh2_scp_recv($this->connect, $remotePath, $localPath) )
        {
            return true;
        }
        else
        {
            throw new FileRemoteDownloadException(NULL, $localPath);
        }
    }

    /**
     * Create Folder
     * 
     * @param string $path
     * @param int    $mode      = 0777
     * @param bool   $recursive = true
     * 
     * @return bool
     */
    public function createFolder(String $path, Int $mode = 0777, Bool $recursive = true) : Bool
    {
        if( @ssh2_sftp_mkdir($this->connect, $path, $mode, $recursive) )
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
        if( @ssh2_sftp_rmdir($this->connect, $path) )
        {
            return true;
        }
        else
        {
            throw new FolderNotFoundException(NULL, $path);
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
        if( @ssh2_sftp_rename($this->connect, $oldName, $newName) )
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
        if( @ssh2_sftp_unlink($this->connect, $path) )
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
        if( @ssh2_sftp_chmod($this->connect, $path, $type) )
        {
            return true;
        }
        else
        {
            throw new InvalidArgumentException(NULL, '$this->connect');
        }
    }

    /**
     * Different Connection
     * 
     * @param array $config
     * 
     * @return SSH
     */
    public function differentConnection(Array $config) : SSH
    {
        return new self($config);
    }

    /**
     * Protected Close
     */
    protected function _close() : Bool
    {
        if( ! empty($this->connect) )
        {
            ssh2_exec($this->connect, 'exit');
            $this->connect = NULL;

            return true;
        }

        return false;
    }

    /**
     * Protected Connect
     */
    protected function _connect($config)
    {
        # Connect Settings
        $host      = $config['host'];
        $port      = $config['port'];
        $user      = $config['user'];
        $password  = $config['password'];
        $methods   = $config['methods'];
        $callbacks = $config['callbacks'];
        
        if(  ! empty($methods) && ! empty($callbacks))
        {
            $this->connect = ssh2_connect($host, $port, $methods, $callbacks);
        }
        elseif( ! empty($methods) )
        {
            $this->connect = ssh2_connect($host, $port, $methods);
        }
        else
        {
            $this->connect = ssh2_connect($host, $port);
        }

        if( empty($this->connect) )
        {
            throw new InvalidArgumentException(NULL, '$this->connect');
        }

        if( ! empty($user) )
        {
            $this->login = ssh2_auth_password($this->connect, $user, $password);
        }

        if( empty($this->login) )
        {
            throw new InvalidArgumentException(NULL, '$this->login');
        }

        return $this;
    }

    /**
     * Default Variables
     */
    protected function _defaultVariables()
    {
        $this->command = '';
    }
}
