<?php namespace ZN\Storage;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Storage
{
    /**
     * Keeps session & cookie common methods.
     * 
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Magic constructor
     * 
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Encode session key & value
     * 
     * @param string $nameAlgo  = NULL
     * @param string $valueAlgo = NULL
     * 
     * @return $this
     */
    public function encode(String $name, String $value)
    {
        return $this->storage->encode($name, $value);
    }
    
    /**
     * Decode only session key
     * 
     * @param string $nameAlgo
     * 
     * @return $this
     */
    public function decode(String $hash)
    {
        return $this->storage->decode($hash);
    }
    
    /**
     * Regenerate status
     * 
     * @param bool $regenerate = true
     * 
     * @return $this
     */
    public function regenerate(Bool $regenerate)
    {
        return $this->storage->regenerate($regenerate);
    }

    /**
     * Insert session
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return bool
     */
    public function insert(String $name, $value) : Bool
    {
        return $this->storage->insert($name, $value);
    }

    /**
     * Select session
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public function select(String $name)
    {
        return $this->storage->select($name);
    }

    /**
     * Delete session
     * 
     * @param string $name
     * 
     * @return bool
     */
    public function delete(String $name) : Bool
    {
        return $this->storage->delete($name);
    }

    /**
     * Select all session
     * 
     * @param void
     * 
     * @return array
     */
    public function selectAll() : Array
    {
        return $this->storage->selectAll();
    }

    /**
     * Delete all session
     * 
     * @param void
     * 
     * @return void
     */
    public function deleteAll() : Bool
    {
        return $this->storage->deleteAll();
    }
}
