<?php namespace ZN\Ability;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Lang;
use ZN\ErrorHandling\Exceptions;

trait Exclusion
{
    /**
     * Magic constructor
     * 
     * @param string $file    = NULL
     * @param string $message = NULL
     * @param mixed  $changed = NULL
     * 
     * @return void
     */
    public function __construct($file = NULL, $message = NULL, $changed = NULL)
    {   
        # If the 1. parameter is set to NULL, 
        # the language contents defined in the exception class are used.
        if( defined('static::lang') && $file === NULL )
        {
            # Language content is being obtained.
            $content = static::lang[Lang::get()] ?? 'No Exception Lang';

            # The 2.($message) parameter is assumed to be the parameter that will contain the statements to be placed.
            $placement = static::lang['placement'] ?? $message;

            # If there is an phrase insertion, the message is rearranged.
            $message = $this->phrasePlacement($content, $placement);
        }
        else
        {
            # If the parameters are set as Lang::select(), 
            # this method is enabled.
            if( is_scalar($data = Lang::default('ZN\CoreDefaultLanguage')::select($file, $message, $changed)) && ! empty($data) )
            {
                $message = $data;
            }
            # If 1. parameter is an exception object, 
            # the contents of the object's message are retrieved.
            elseif( is_object($file) )
            {
                $message = $file->getMessage();
            }
            # The 1. parameter can be used directly as message content.
            else
            {
                $message = $file;
            }    
        }
        
        # The constructor method of the exception class goes into effect.
        parent::__construct($message);
    }

    /**
     * Code continue
     * 
     * @param void
     * 
     * @return void
     */
    public function continue()
    {
        echo Exceptions::continue($this->getMessage(), $this->getFile(), $this->getLine());
    }

    /**
     * Protected phrase placement
     */
    protected function phrasePlacement($content, $placement)
    {
        if( is_array($placement) )
        {
            return str_replace(array_keys($placement), array_values($placement), $content);
        }

        return str_replace('%', $placement, $content);
    }
}