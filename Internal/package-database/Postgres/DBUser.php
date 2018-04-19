<?php namespace ZN\Database\Postgres;
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
use ZN\Database\DriverUser;

class DBUser extends DriverUser
{
    /**
     * Protected Postgre Quote Options
     * 
     * @var array
     */
    protected $postgreQuoteOptions =
    [
        'PASSWORD',
        'VALID UNTIL'
    ];

    /**
     * Sets name
     * 
     * @param string
     */
    public function name($name)
    {
        $this->name = $name;
    }

    /**
     * Sets option
     * 
     * @param string $option
     * @param string $value
     */
    public function option($option, $value)
    {
        if( ! empty($this->postgreQuoteOptions[strtoupper($option)]) )
        {
            $value = Base::presuffix($value, '\'');
        }

        $this->parameters['option'] = $option.' '.$value;
    }

    /**
     * Create User
     * 
     * @param string $use
     * 
     * @return string
     */
    public function create($user)
    {
        $query = 'CREATE USER '.
                 $user.
                 ( ! empty($this->parameters['option']) ? ' '.$this->parameters['option'] : '' );

        $this->_resetQuery();

        return $query;
    }

    /**
     * Drop User
     * 
     * @param string $use
     * 
     * @return string
     */
    public function drop($user)
    {
        $query = 'DROP USER '.$user;

        $this->_resetQuery();

        return $query;
    }

    /**
     * Alter User
     * 
     * @param string $use
     * 
     * @return string
     */
    public function alter($user)
    {
        $query = 'ALTER USER '.
                 $user.
                 ( ! empty($this->parameters['option']) ? ' '.$this->parameters['option'] : '' );

        $this->_resetQuery();

        return $query;
    }
}
