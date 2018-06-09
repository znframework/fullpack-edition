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

trait Information
{
    /**
     * Catch error
     * 
     * @var mixed
     */
    protected $error;

     /**
     * Catch success
     * 
     * @var mixed
     */
    protected $success;

    /**
     * Classes that incorporate this feature include a structure that can hold error messages.
     * 
     * @param string $endOfLine = '<br>'
     * 
     * @return mixed
     */
    public function error(String $endOfLine = '<br>')
    {
        if( ! empty($this->error) )
        {
            if( is_array($this->error) )
            {
                return implode($endOfLine, $this->error);
            }

            return $this->error;
        }
        else
        {
            return false;
        }
    }

    /**
     * Classes that incorporate this feature include a structure that can hold success messages.
     * 
     * @param string $endOfLine = '<br>'
     * 
     * @return mixed
     */
    public function success(String $endOfLine = '<br>')
    {
        if( empty($this->error) )
        {
            if( ! empty($this->success) )
            {
                if( is_array($this->success) )
                {
                    return implode($endOfLine, $this->success);
                }

                return $this->success;
            }
            else
            {
                return Lang::select('Success', 'success');
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Classes that incorporate this feature include a structure that can hold error or success messages.
     * 
     * @return mixed
     */
    public function status()
    {
        if( $success = $this->success() )
        {
            return $success;
        }

        return $this->error();
    }
}
