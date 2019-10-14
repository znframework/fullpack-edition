<?php namespace ZN\Hypertext;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Inclusion;
use ZN\Buffering\Callback;

class Script implements TextInterface
{
    /**
     * type
     * 
     * @var string
     */
    protected $type = 'text/javascript';

    /**
     * Compress Javascript codes
     * 
     * @param callback $script
     * @param string   $encoding     = 'normal' - [none|numeric|normal|ascii]
     * @param bool     $fastDecode   = true
     * @param bool     $specialChars = false
     */
    public function compress(Callable $callback, String $encoding = 'normal', Bool $fastDecode = true, Bool $specialChars = false)
    {
        $output = Callback::do($callback);

        $packer = new ScriptPacker($output, $encoding, $fastDecode, $specialChars);
    
        return $packer->pack();
    }

    /**
     * Sets the [type] property of the [script] tag.
     * 
     * @param string $type
     * 
     * @return $this
     */
    public function type(String $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Imports script libraries.
     * 
     * @param string ...$libraries
     * 
     * @return $this
     */
    public function library(...$libraries)
    {
        Inclusion\Script::use(...$libraries);

        return $this;
    }

     /**
     * Opens the [script] tag.
     * 
     * @param void
     * 
     * @return string
     */
    public function open() : String
    {
        $script = "<script type=\"$this->type\">".EOL;

        return $script;
    }

    /**
     * Closes the [/script] tag.
     * 
     * @param void
     * 
     * @return string
     */
    public function close() : String
    {
        $script =  '</script>'.EOL;
        return $script;
    }
}
