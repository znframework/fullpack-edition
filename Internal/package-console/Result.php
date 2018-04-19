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
    protected $title = 'Result';

    /**
     * Magic constructor
     * 
     * @param mixed $result
     * 
     * @return void
     */
    public function __construct($result)
    {
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
                        print_r($result);

                        $this->print = 'array';
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
            exit($this->return);
        }

        $line = $this->line();

        echo $line;
        echo $this->title();                                                                                         
        echo $line;
        echo $this->content();
        echo $line;
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
}