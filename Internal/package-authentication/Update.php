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

class Update extends UserExtends
{
    /**
     * Controls old password
     * 
     * @param string $oldPassword
     */
    public function oldPassword(string $oldPassword)
    {
        Properties::$parameters['oldPassword'] = $oldPassword;
    }

    /**
     * New Password
     * 
     * @param string $newPassword
     */
    public function newPassword(string $newPassword)
    {
        Properties::$parameters['newPassword'] = $newPassword;
    }

    /**
     * Password Again
     * 
     * @param string $passwordAgain
     */
    public function passwordAgain(string $passwordAgain)
    {
        Properties::$parameters['passwordAgain'] = $passwordAgain;
    }

    /**
     * Do Update
     * 
     * @param string       $old      = NULL
     * @param string       $new      = NULL
     * @param string       $newAgain = NULL
     * @param string|array $data     = []
     * 
     * @return bool
     */
    public function do(string $old = NULL, string $new = NULL, string $newAgain = NULL, $data = []) : bool
    {
        if( $this->isLogin() )
        {
            $this->autoMatchColumns($data);
            $this->controlPropertiesParameters($old, $new, $newAgain, $data);

            if( empty($newAgain) )
            {
                $newAgain = $new;
            }

            $oldPassword      = $this->getEncryptionPassword($old);
            $newPassword      = $this->getEncryptionPassword($new);
            $newPasswordAgain = $this->getEncryptionPassword($newAgain);

            if( ! empty($this->joinTables) )
            {
                $joinData = $data;
                $data     = $data[$this->tableName] ?? [$this->tableName];
            }

            $getUserData = $this->getUserData();
            $username    = $getUserData->{$this->usernameColumn};
            $password    = $getUserData->{$this->passwordColumn};

            if( $oldPassword != $password )
            {
                return $this->setErrorMessage('oldPasswordError');
            }
            elseif( $newPassword != $newPasswordAgain )
            {
                return $this->setErrorMessage('passwordNotMatchError');
            }
            else
            {
                $data[$this->passwordColumn] = $newPassword;
                $data[$this->usernameColumn] = $username;

                if( ! empty($this->joinTables) )
                {
                    $joinCol = $this->getJoinColumnByUsername($username);

                    foreach( $this->joinTables as $table => $joinColumn )
                    {
                        if( isset($joinData[$table]) )
                        {
                            $this->updateUserData($table, $joinColumn, $joinCol, $joinData[$table]);
                        }
                    }
                }
                else
                {
                    if( ! $this->updateUserData($this->tableName, $this->usernameColumn, $username, $data) )
                    {
                        return $this->setErrorMessage('registerUnknownError'); // @codeCoverageIgnore
                    }
                }

                return $this->setSuccessMessage('updateProcessSuccess');
            }
        }
        else
        {
            return false; // @codeCoverageIgnore
        }
    }

    /**
     * Protected update user data
     */
    protected function updateUserData($table, $column, $value, $data)
    {
        return $this->dbClass->where($column, $value)->update($table, $data);
    }

    /**
     * Protected get join column by username
     */
    protected function getJoinColumnByUsername($username)
    {
        return $this->dbClass->where($this->usernameColumn, $username)->get($this->tableName)->row()->{$this->joinColumn};
    }

    /**
     * Protected is login
     */
    protected function isLogin()
    {
        return (new Login)->is();
    }

    /**
     * Protected get user data
     */
    protected function getUserData()
    {
        return (new Data)->get($this->tableName);
    }

    /**
     * Protected control properties parameters
     */
    protected function controlPropertiesParameters(&$old, &$new, &$newAgain, &$data)
    {
        $old      = Properties::$parameters['oldPassword']   ?? $old;
        $new      = Properties::$parameters['newPassword']   ?? $new;
        $newAgain = Properties::$parameters['passwordAgain'] ?? $newAgain;
        $data     = Properties::$parameters['column']        ?? $data;

        Properties::$parameters = [];
    }
}
