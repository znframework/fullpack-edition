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
     * tag
     * 
     * @var bool
     */
    protected $tag = false;

    /**
     * type
     * 
     * @var string
     */
    protected $type = 'text/javascript';

    /**
     * Tag
     */
    public function tag()
    {
        $this->tag = true;

        return $this;
    }

    /**
     * Compress Javascript codes
     * 
     * @param callback $script
     * @param string   $encoding     = 'normal' - [none|numeric|normal|ascii]
     * @param bool     $fastDecode   = true
     * @param bool     $specialChars = false
     * 
     * @return string
     */
    public function compress(callable $callback, string $encoding = 'normal', bool $fastDecode = true, bool $specialChars = false)
    {
        $output = Callback::do($callback);

        $packer = new ScriptPacker($output, $encoding, $fastDecode, $specialChars);
    
        $pack = $packer->pack();

        if( $this->tag )
        {
            $pack = $this->open() . $pack . $this->close();

            $this->tag = false;
        }

        return $pack;
    }

    /**
     * Sets the [type] property of the [script] tag.
     * 
     * @param string $type
     * 
     * @return $this
     */
    public function type(string $type)
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
    public function open() : string
    {
        $script = "<script type=\"$this->type\">".EOL;

        $this->default();

        return $script;
    }

    /**
     * Closes the [/script] tag.
     * 
     * @param void
     * 
     * @return string
     */
    public function close() : string
    {
        $script =  '</script>'.EOL;
        return $script;
    }

    /**
     * protected default
     */
    protected function default()
    {
        $this->type = 'text/javascript';
    }
}
