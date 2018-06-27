<?php namespace ZN\Console;
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
use ZN\Buffering;

class Result
{
    protected $title = 'RESULT';

    /**
     * Magic constructor
     * 
     * @param mixed $result
     * 
     * @return void
     */
    public function __construct($result, $title = NULL)
    {
        if( $title !== NULL )
        {
            $this->title = $title;
        }

        $this->return = Buffering\Callback::do(function() use($result)
        {
            $success = Lang::select('Success', 'success');
            $error   = Lang::select('Error', 'error');
            $nodata  = 'No Data';
    
            if( $result === true || $result === NULL )
            {
                echo $success;
            }
            elseif( $result === false )
            {
                echo $error;
            }
            else
            {
                if( is_array($result) )
                {
                    if( ! empty($result) )
                    {
                        $this->print = $result;
                    }
                    else
                    {
                        echo $nodata;
                    }
                }
                else
                {
                    if( ! empty($result) )
                    {
                        echo $result;
                    }
                    else
                    {
                        echo $nodata;
                    }
                }
            }
        });

        if( isset($this->print) ) 
        {
            $this->outputMultipleResult();
        }
        else
        {
            $this->outputSingleResult();
        } 
    }

    /**
     * Protected Line
     */
    protected function line()
    {
        return '+' . str_repeat
        (
            '-', 
            (($returnLength = strlen($this->return)) < ($titleLength = (strlen($this->title))) 
                                                     ? $titleLength 
                                                     : $returnLength
            )) . '--+' . EOL;
    }

    /**
     * Protected Title
     */
    protected function title()
    {
        return '| '.$this->title.' ' . str_repeat
        (
            ' ', 
            ($returnLength = strlen($this->return)) >= ($titleLength = strlen($this->title)) 
                                                    ? $returnLength - $titleLength
                                                    : 0
            ) . '|' . EOL;
    }

    /**
     * Protected Content
     */
    protected function content()
    {
        return '| ' . $this->return . str_repeat
        (
            ' ', 
            (($returnLength = strlen($this->return)) < ($titleLength = strlen($this->title)) 
                                                     ? $titleLength - $returnLength + 1
                                                     : 1
            )) . '|'.EOL;
    }

    /**
     * Protected output single result
     */
    protected function outputSingleResult()
    {
        echo $this->line();
        echo $this->title();                                                                                         
        echo $this->line();
        echo $this->content();
        echo $this->line();
    }

    /**
     * Protected output multiple result
     */
    protected function outputMultipleResult()
    {
        $this->return = $max = max($this->print) . '    ';

        echo $this->line();
        echo $this->title();                                                                                         
        echo $this->line();

        foreach( $this->print as $key => $ret )
        {
            $diff = strlen($max) - strlen($return = $key . ' | ' . $ret);
            $this->return = $return . str_repeat(' ', $diff);            
        
            echo $this->content();
            echo $this->line();
        }
    }
}