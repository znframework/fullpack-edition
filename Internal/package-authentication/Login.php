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
     */
    public function username(String $username)
    {
        Properties::$parameters['username'] = $username;
    }

    /**
     * Password
     * 
     * @param string $password
     */
    public function password(String $password)
    {
        Properties::$parameters['password'] = $password;
    }

    /**
     * Remember
     * 
     * @param bool $remember = true
     */
    public function remember(Bool $remember = true)
    {
        Properties::$parameters['remember'] = $remember;
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
        $rpassword = $password;

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

        if
        ( 
            ! empty($r->{$this->usernameColumn}) && ($r->{$this->passwordColumn} == $password || ($this->spectator !== NULL && $this->spectator == $rpassword)) )
        {
            if( $this->spectator !== NULL )
            {
                $password = $r->{$this->passwordColumn};
            }

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
        $username = $this->cookieClass->select($this->getUniqueUsernameKey());
        $password = $this->cookieClass->select($this->getUniquePasswordKey());
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
        $this->sessionClass->insert($this->getUniqueUsernameKey(), $username);
        $this->sessionClass->insert($this->getUniquePasswordKey(), $password);

        return true;
    }

    /**
     * Protected start permanent user session with cookie
     */
    protected function startPermanentUserSessionWithCookie($username, $password)
    {
        if( $this->cookieClass->select($uniqueUsernameKey = $this->getUniqueUsernameKey()) !== $username )
        {
            $this->cookieClass->insert($uniqueUsernameKey, $username);
            $this->cookieClass->insert($this->getUniquePasswordKey(), $password);
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
