<?php namespace ZN\Authentication;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Login extends UserExtends
{
    /**
     * Username
     * 
     * @param string $username
     * 
     * @return Login
     */
    public function username(String $username) : Login
    {
        Properties::$parameters['username'] = $username;

        return $this;
    }

    /**
     * Password
     * 
     * @param string $password
     * 
     * @return Login
     */
    public function password(String $password) : Login
    {
        Properties::$parameters['password'] = $password;

        return $this;
    }

    /**
     * Remember
     * 
     * @param bool $remember = true
     * 
     * @return Login
     */
    public function remember(Bool $remember = true) : Login
    {
        Properties::$parameters['remember'] = $remember;

        return $this;
    }

    /**
     * Do Login
     * 
     * @param string $username   = NULL
     * @param string $password   = NULL
     * @param mixed  $rememberMe = false
     * 
     * @return bool
     */
    public function do(String $username = NULL, String $password = NULL, $rememberMe = false) : Bool
    {
        $this->controlPropertiesParameters($username, $password, $rememberMe);

        if( ! is_scalar($rememberMe) )
        {
            $rememberMe = false;
        }

        $password = $this->getEncryptionPassword($password);

        $this->_multiUsernameColumns($username);

        $r = $this->getUserTableByUsername($username)->row();

        if( ! isset($r->{$this->passwordColumn}) )
        {
            return $this->setErrorMessage('loginError');
        }

        if( ! empty($this->bannedColumn) )
        {
            $bannedControl = $r->{$this->bannedColumn};
        }

        if( ! empty($this->activationColumn) )
        {
            $activationControl = $r->{$this->activationColumn};
        }

        if( ! empty($r->{$this->usernameColumn}) && $r->{$this->passwordColumn} == $password )
        {
            if( ! empty($this->bannedColumn) && ! empty($bannedControl) )
            {
                return $this->setErrorMessage('bannedError');
            }

            if( ! empty($this->activationColumn) && empty($activationControl) )
            {
                return $this->setErrorMessage('activationError');
            }

            $this->startUserSession($username, $password);

            if( ! empty($rememberMe) )
            {
               $this->startPermanentUserSessionWithCookie($username, $password);
            }

            if( ! empty($this->activeColumn) )
            {
                $this->setUserStateActive($username);
            }

            return $this->setSuccessMessage('loginSuccess');
        }
        else
        {
            return $this->setErrorMessage('loginError');
        }
    }

    /**
     * Is Login
     * 
     * @param void
     * 
     * @return bool
     */
    public function is() : Bool
    {
        $getUserData = $this->getUserData();

        if( ! empty($this->bannedColumn) && ! empty($getUserData->{$this->bannedColumn}) )
        {
            $this->logout();
        }

        $this->rememberUsernameAndPassword($cUsername, $cPassword);

        if( isset($getUserData->{$this->usernameColumn}) )
        {
            $isLogin = true;
        }
        elseif( $this->userExists($cUsername, $cPassword) )
        {
            $isLogin = $this->startUserSession($cUsername, $cPassword);
        }
        else
        {
            $isLogin = false;
        }

        return $isLogin;
    }

    /**
     * Protected user exists
     */
    protected function userExists($username, $password)
    {
        if( ! empty($username) && ! empty($password) )
        {
            return $this->dbClass->where($this->usernameColumn, $username, 'and')
                                 ->where($this->passwordColumn, $password)
                                 ->get($this->tableName)
                                 ->totalRows();
        }
        
        return false;
    }

    /**
     * Protected remember username and password
     */
    protected function rememberUsernameAndPassword(&$username, &$password)
    {
        $username = $this->cookieClass->select($this->usernameColumn);
        $password = $this->cookieClass->select($this->passwordColumn);
    }

     /**
     * Protected set user state active
     */
    protected function setUserStateActive($username)
    {
        $this->dbClass->where($this->usernameColumn, $username)
                     ->update($this->tableName, [$this->activeColumn => 1]);
    }

    /**
     * Protected start user session
     */
    protected function startUserSession($username, $password)
    {
        $this->sessionClass->insert($this->usernameColumn, $username);
        $this->sessionClass->insert($this->passwordColumn, $password);

        return true;
    }

    /**
     * Protected start permanent user session with cookie
     */
    protected function startPermanentUserSessionWithCookie($username, $password)
    {
        if( $this->cookieClass->select($this->usernameColumn) !== $username )
        {
            $this->cookieClass->insert($this->usernameColumn, $username);
            $this->cookieClass->insert($this->passwordColumn, $password);
        }
    }

    /**
     * Protected get user data
     */
    protected function getUserData()
    {
        return (new Data)->get($this->tableName);
    }

    /**
     * Protected logout
     */
    protected function logout()
    {
        (new Logout)->do();
    }

    /**
     * Protected control properties parameters
     */
    protected function controlPropertiesParameters(&$username, &$password, &$rememberMe)
    {
        $username   = Properties::$parameters['username'] ?? $username;
        $password   = Properties::$parameters['password'] ?? $password;
        $rememberMe = Properties::$parameters['remember'] ?? $rememberMe;

        Properties::$parameters = [];
    }
}
