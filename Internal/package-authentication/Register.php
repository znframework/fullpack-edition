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

use ZN\IS;
use ZN\Base;
use ZN\Singleton;
use ZN\Request\URL;
use ZN\Request\URI;
use ZN\Response\Redirect;

class Register extends UserExtends
{
    /**
     * Auto login.
     * 
     * @param mixed $autoLogin = true
     */
    public function autoLogin($autoLogin = true)
    {
        Properties::$parameters['autoLogin'] = $autoLogin;
    }

    /**
     * Do register.
     * 
     * @param string|array  $data                 = NULL
     * @param mixed         $autoLogin            = false
     * @param string        $activationReturnLink = ''
     * 
     * @return Bool
     */
    public function do($data = NULL, $autoLogin = false, string $activationReturnLink = '') : bool
    {
        $this->autoMatchColumns($data);
        $this->controlPropertiesParameters($data, $autoLogin, $activationReturnLink);
        
        if( ! empty($this->joinTables) )
        {
            $joinData = $data;
            $data     = $data[$this->tableName] ?? [$this->tableName];
        }

        if( ! isset($data[$this->usernameColumn]) || ! isset($data[$this->passwordColumn]) )
        {
            return $this->setErrorMessage('registerUsernameError');
        }

        $loginUsername   = $data[$this->usernameColumn];
        $loginPassword   = $data[$this->passwordColumn];
        $encodePassword  = $this->getEncryptionPassword($loginPassword);
        $usernameControl = $this->getTotalRowsByUsername($loginUsername);

        if( empty($usernameControl) )
        {
            $data[$this->passwordColumn] = $encodePassword;

            if( ! $this->registerUserInformations($data) )
            {
                return $this->setErrorMessage('registerUnknownError');
            }

            if( ! empty($this->joinTables) )
            {
                $this->insertJoinUserDataByUsername($loginUsername, $joinData);
            }

            $this->setSuccessMessage('registerSuccess');

            if( ! empty($this->activationColumn) )
            {
                if( ! IS::email($loginUsername) )
                {
                    $email = $data[$this->emailColumn] ?? NULL;
                }
                else
                {
                    $email = NULL;
                }

                if( empty($activationReturnLink) )
                {
                    throw new Exception\ActivationReturnLinkNotFoundException;
                }
                
                $this->sendActivationEmail($loginUsername, $encodePassword, $activationReturnLink, $email);
            }
            else
            {
                if( $autoLogin === true )
                {
                    $this->doLogin($loginUsername, $loginPassword);
                }
                elseif( is_string($autoLogin) )
                {
                    $this->redirectAutoLogin($autoLogin);
                }
            }

            return true;
        }
        else
        {
            return $this->setErrorMessage('registerError');
        }
    }

    /**
     * Activation complete.
     * 
     * 5.7.3[changed]
     * 
     * @param string|int          $userUriKey = 'user
     * @param string|int|callable $decryptor  = 'pass'
     * 
     * @return bool
     */
    public function activationComplete($userUriKey = 'user', $decryptor = 'pass') : bool
    {
        # Return link values.
        # 5.7.3[added]
        if( is_scalar($userUriKey) )
        {
            $user = URI::get($userUriKey); 
        }
        # invalid usage
        else
        {
            throw new Exception\InvalidArgumentException(NULL, '1.');
        }
        
        # 5.7.3[added]
        # scalar
        if( is_scalar($decryptor) )
        {
            $pass = URI::get($decryptor);
        }
        # callable
        elseif( is_callable($decryptor) )
        {
            $pass = $decryptor();
        }
        # invalid usage
        else
        {
            throw new Exception\InvalidArgumentException(NULL, '2.');
        }

        if( ! empty($user) && ! empty($pass) )
        {
            $row = $this->getUserDataByUsernameAndPassword($user, $pass);

            if( ! empty($row) )
            {
                $this->updateUserActivationColumnByUsername($user);

                return $this->setSuccessMessage('activationComplete');
            }
            else
            {
                return $this->setErrorMessage('activationCompleteError');
            }
        }
        else
        {
            return $this->setErrorMessage('activationCompleteError');
        }
    }

    /**
     * Resend activation e-mail.
     * 
     * @param string $username
     * @param string $returnLink
     * @param string $email = NULL
     * 
     * @return bool
     */
    public function resendActivationEmail(string $username, string $returnLink, string $email = NULL) : bool
    {
        if( empty($this->activationColumn) )
        {
            throw new Exception\ActivationColumnException;
        }

        $data = $this->isResendActivationEmailByValue($email ?? $username);
        
        if( empty($data) )
        {
            return $this->setErrorMessage('resendActivationError');
        }
        
        return $this->sendActivationEmail($username, $data->{$this->passwordColumn}, $returnLink, $email);
    }

    /**
     * Protected send activation email
     * 
     * @param string $user
     * @param string $pass 
     * @param string $activationReturnLink
     * @param string $email
     * 
     * @return bool
     */
    protected function sendActivationEmail($user, $pass, $activationReturnLink, $email)
    {
        $url = Base::suffix($activationReturnLink);

        if( ! IS::url($url) )
        {
            $url = URL::site($url);
        }

        # 5.7.3[added]
        # Sets activation email content
        $message = $this->getEmailTemplate
        ([
            'url'  => $url,
            'user' => $user,
            'pass' => $pass
        ], 'Activation');

        $user = $email ?? $user;

        if( ! IS::email($user) )
        {
            throw new Exception\InvalidEmailException(NULL, $user);
        }

        $emailclass = Singleton::class('ZN\Email\Sender');

        $emailclass->sender($this->senderMail, $this->senderName)
                   ->receiver($user, $user)
                   ->subject(Properties::$setEmailTemplateSubject ?? $this->getLang['activationProcess'])
                   ->content($message);

        Properties::$setEmailTemplateSubject = NULL;

        if( $emailclass->send() )
        {
            return $this->setSuccessMessage('activationEmail');
        }
        else
        {
            return $this->setErrorMessage('emailError'); // @codeCoverageIgnore
        }
    }

    /**
     * Protected is resend activation email by value
     */
    protected function isResendActivationEmailByValue($value)
    {
        return $this->dbClass->where($this->usernameColumn, $value, 'and')
                    ->where($this->activationColumn, '0')
                    ->get($this->tableName)
                    ->row();
    }

    /**
     * Protected get user data by username and password
     */
    protected function getUserDataByUsernameAndPassword($username, $password)
    {
        return $this->dbClass->where($this->usernameColumn, $username, 'and')
                             ->where($this->passwordColumn, $password)
                             ->get($this->tableName)
                             ->row();
    }

    /**
     * Protected update user activation column by username
     */
    protected function updateUserActivationColumnByUsername($username)
    {
        $this->dbClass->where($this->usernameColumn, $username)
                              ->update($this->tableName, [$this->activationColumn => '1']);
    }

    /**
     * Protected redirect auto login
     */
    protected function redirectAutoLogin($path)
    {
        new Redirect($path, 0, [], Properties::$redirectExit);
    }

    /**
     * Get total rows by username
     */
    protected function getTotalRowsByUsername($username)
    {
        return $this->getUserTableByUsername($username)->totalRows();
    }

    /**
     * Get join column by username
     */
    protected function getJoinColumnByUsername($username)
    {
        return $this->getUserTableByUsername($username)->row()->{$this->joinColumn};
    }

    /**
     * Protected insert join user data by username
     */
    protected function insertJoinUserDataByUsername($username, &$joinData)
    {
        $joinCol = $this->getJoinColumnByUsername($username);

        foreach( $this->joinTables as $table => $joinColumn )
        {
            $joinData[$table][$this->joinTables[$table]] = $joinCol;

            $this->dbClass->insert($table, $joinData[$table]);
        }
    }

    /**
     * Protected register user informations
     */
    protected function registerUserInformations($data)
    {
        return $this->dbClass->insert($this->tableName, $data);
    }

    /**
     * Protected do login
     */
    protected function doLogin($username, $password)
    {
        (new Login)->do($username, $password);
    }

    /**
     * Protected control properties parameters
     */
    protected function controlPropertiesParameters(&$data, &$autoLogin, &$activationReturnLink)
    {
        $data                   = Properties::$parameters['column']     ?? $data;
        $autoLogin              = Properties::$parameters['autoLogin']  ?? $autoLogin;
        $activationReturnLink   = Properties::$parameters['returnLink'] ?? $activationReturnLink;

        Properties::$parameters = [];
    }
}
