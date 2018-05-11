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

use ZN\Response\Redirect;

class Logout extends UserExtends
{
    /**
     * Do Logout
     * 
     * @param string $redirectUrl = NULL
     * @param int    $time        = 0
     * 
     * @return void
     */
    public function do(String $redirectUrl = NULL, Int $time = 0)
    {
        if( $this->isUserStateActive() !== NULL )
        {
            if( ! empty($this->activeColumn) )
            {
                $this->setUserStatePassive($this->isUserStateActive());
            }

            $this->endUserProcessAndRedirect((string) $redirectUrl, $time);
        }
    }

    /**
     * Protected is user state active
     */
    protected function isUserStateActive()
    {
        return (new Data)->get($this->tableName)->{$this->usernameColumn} ?? NULL;
    }

    /**
     * Protected set user state passive
     */
    protected function setUserStatePassive($username)
    {
        $this->dbClass->where($this->usernameColumn, $username)
                     ->update($this->tableName, [$this->activeColumn => 0]);
    }

    /**
     * Protected end user process
     */
    protected function endUserProcessAndRedirect($redirectUrl, $time)
    {
        $this->cookieClass ->delete($this->usernameColumn);
        $this->cookieClass ->delete($this->passwordColumn);
        $this->sessionClass->delete($this->usernameColumn);

        new Redirect((string) $redirectUrl, $time);
    }
}
