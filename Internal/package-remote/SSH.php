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

use ZN\Base;
use ZN\Config;
use ZN\Support;
use ZN\Remote\Exception\FileNotFoundException;
use ZN\Remote\Exception\FileRemoteUploadException;
use ZN\Remote\Exception\FileRemoteDownloadException;
use ZN\Remote\Exception\FolderChangeNameException;
use ZN\Remote\Exception\FolderNotFoundException;
use ZN\Remote\Exception\FolderAllreadyException;
use ZN\Remote\Exception\LoginErrorException;
use ZN\Remote\Exception\ConnectionErrorException;

/**
 * @codeCoverageIgnore
 */
class SSH extends RemoteExtends implements SSHInterface, RemoteInterface
{
    /**
     * Connect
     * 
     * @var resource
     */
    protected $connect = NULL;

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
    public function __construct(array $config = [])
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
    public function command(string $command) : SSH
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
    public function run(string $command = NULL)
    {
        if( ! empty($this->connect) )
        {
            if( ! empty($this->command) )
            {
                $command = Base::removeSuffix($this->command);
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
    public function output(int $length = 4096) : string
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
    public function upload(string $localPath, string $remotePath) : bool
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
    public function download(string $remotePath, string $localPath) : bool
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
    public function createFolder(string $path, int $mode = 0777, bool $recursive = true) : bool
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
    public function deleteFolder(string $path) : bool
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
    public function rename(string $oldName, string $newName) : bool
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
    public function deleteFile(string $path) : bool
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
    public function permission(string $path, int $type = 0755) : bool
    {
        if( @ssh2_sftp_chmod($this->connect, $path, $type) )
        {
            return true;
        }
        
        return false;
    }

    /**
     * Protected Close
     */
    protected function _close() : bool
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
        
        if( ! empty($methods) && ! empty($callbacks) )
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
            throw new ConnectionErrorException;
        }

        if( ! empty($user) )
        {
            if( ! ssh2_auth_password($this->connect, $user, $password) )
            {
                throw new LoginErrorException;
            }
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
