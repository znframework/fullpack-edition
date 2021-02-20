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

use ZN\Database\DriverUser;

class DBUser extends DriverUser
{
    /**
     * Protected Postgre Quote Options
     * 
     * @var array
     */
    protected $options =
    [
        'PASSWORD',
        'VALID UNTIL'
    ];

    /**
     * Protected role names
     * 
     * @var array
     */
    protected $roleNames = 
    [
        'current' => 'CURRENT_USER',
        'session' => 'SESSION_USER',
        'public'  => 'PUBLIC'
    ];

    /**
     * Grant Option
     * 
     * @var string
     */
    protected $grantOption;

    /**
     * Sets Auth Password
     * 
     * @param string $password
     */
    public function password($password)
    {
        $this->option('password', $password);
    }

    /**
     * Password Expire
     * 
     * @param string $date
     */
    public function passwordExpire(String $date, $n = NULL)
    {
        $this->option('valid until', $date);
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
        $query = 'CREATE USER ' . $user . $this->implodeOption();
                 
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
        $query = 'DROP USER ' . $user;

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
    public function alter($role = 'current')
    {
        $query = 'ALTER USER ' . ($this->roleNames[$role] ?? $role) . $this->implodeOption();

        $this->_resetQuery();

        return $query;
    }

    /**
     * Grant
     * 
     * @param string $name 
     * @param string $type
     * @param string $select
     * 
     * @return bool
     */
    public function grant($name, $type, $select)
    {
        $query = 'GRANT ' . $name . ' ON ' . ($this->type ?: $type) . ($this->select ?: $select) . ' TO ' . $this->name . $this->grantOption . ';';
      
        $this->_resetQuery();

        return $query;
    }

    /**
     * Revoke
     * 
     * @param string $name 
     * @param string $type
     * @param string $select
     * 
     * @return bool
     */
    public function revoke($name, $type, $select)
    {
        $query = 'REVOKE ' . $name . ' ON ' . ($this->type ?: $type) . ($this->select ?: $select) . ' FROM ' . $this->name . ';';

        $this->_resetQuery();

        return $query;
    }

    /**
     * Rename
     * 
     * @param string $oldName
     * @param string $newName
     */
    public function rename($oldName, $newName)
    {
        $query = 'ALTER USER ' . $oldName.' RENAME TO ' . $newName . ';';

        return $query;
    }

    /**
     * Name
     */
    public function name($name)
    {
        $this->name = $name;
    }

    /**
     * Grant Option
     */
    public function grantOption()
    {
        $this->grantOption = ' WITH GRANT OPTION ';
    }

    /**
     * Reset Query
     */
    protected function _resetQuery()
    {
        $this->parameters  = [];
        $this->select      = NULL;
        $this->name        = NULL;
        $this->type        = NULL;
        $this->grantOption = NULL;
    }
}
