<?php namespace ZN\Compression;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Ability\Driver;

class Force implements ForceInterface
{
    use Driver;

    /**
     * Driver
     * 
     * @param array driver
     */
    const driver =
    [
        'options'   => ['gz', 'bz', 'lzf', 'rar', 'zip', 'zlib'],
        'namespace' => 'ZN\Compression\Drivers',
        'default'   => 'ZN\Compression\CompressionDefaultConfiguration',
        'config'    => 'Storage:compression'
    ];

    /**
     * Extract data
     * 
     * @param string $source
     * @param string $target   = NULL
     * @param string $password = NULL
     * 
     * @return bool
     */
    public function extract(string $source, string $target = NULL, string $password = NULL) : bool
    {
        if( ! is_file($source) )
        {
            throw new Exception\FileNotFoundException(NULL, $source);
        }

        return $this->driver->extract($source, $target, $password);
    }

    /**
     * Write data to file
     * 
     * @param string $file
     * @param string $data
     * 
     * @return bool
     */
    public function write(string $file, string $data) : bool
    {
        return $this->driver->write($file, $data);
    }

    /**
     * Read file
     * 
     * @param string $file
     * 
     * @return bool
     */
    public function read(string $file) : string
    {
        return $this->driver->read($file);
    }

    /**
     * Force do
     * 
     * @param string $data
     * 
     * @return string
     */
    public function do(string $data) : string
    {
        return $this->driver->do($data);
    }

    /**
     * Force undo
     * 
     * @param string $data
     * 
     * @return string
     */
    public function undo(string $data) : string
    {
        return $this->driver->undo($data);
    }
}
