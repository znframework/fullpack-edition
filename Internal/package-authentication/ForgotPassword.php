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
use ZN\Cryptography\Encode;

class ForgotPassword extends UserExtends
{
    /**
     * Email
     * 
     * @param string $email
     */
    public function email(String $email)
    {
        Properties::$parameters['email'] = $email;
    }

    /**
     * Verification
     * 
     * @param string $verification
     * 
     * @return ForgotPassword
     */
    public function verification(String $verification)
    {
        Properties::$parameters['verification'] = $verification;
    }

    /**
     * Password change process
     * 
     * @param string $changePassword
     * 
     * @return ForgotPassword
     */
    public function passwordChangeProcess(String $changePassword = 'before')
    {
        Properties::$parameters['changePassword'] = $changePassword;
    }

    /**
     * Forgot Password
     * 
     * @param string $email          = NULL
     * @param string $returnLinkPath
     * @param string $changePassword = 'before'
     * 
     * @return bool
     */
    public function do(String $email = NULL, String $returnLinkPath, String $changePassword = 'before') : Bool
    {
        $this->controlPropertiesParameters($email, $verification, $returnLinkPath, $changePassword);

        $row = $this->getUserDataRowByEmail($email);

        if( isset($row->{$this->usernameColumn}) )
        {
            if( ! empty($this->verificationColumn) )
            {
                if( $verification !== $row->{$this->verificationColumn} )
                {
                    return $this->setErrorMessage('verificationOrEmailError');
                }
            }
            
            if( ! IS::url($returnLinkPath) )
            {
                $returnLinkPath = URL::site($returnLinkPath);
            }

            $newPassword    = $this->createRandomNewPassword();
            $encodePassword = $this->getEncryptionPassword($newPassword);

            $message = $this->getEmailTemplate
            ([
                'user' => $username = $row->{$this->usernameColumn},
                'pass' => $newPassword,
                'url'  => $this->encryptionReturnLink($returnLinkPath, $username, $encodePassword)
            ], 'ForgotPassword');

            if( $this->sendForgotPasswordEmail($email, $message) )
            {
                if( $changePassword === 'before' )
                {
                    if( $this->updateUserPassword($email, $encodePassword) )
                    {
                        return $this->setSuccessMessage('forgotPasswordSuccess');
                    }

                    return $this->setErrorMessage('updateError');
                }

                return $this->setSuccessMessage('forgotPasswordSuccess');
            }
            else
            {
                return $this->setErrorMessage('emailError');
            }
        }
        else
        {
            return $this->setErrorMessage('forgotPasswordError');
        }
    }

    /**
     * Password change complete
     */
    public function passwordChangeComplete(String $redirect = NULL)
    {
        $this->decryptionReturnLink($username, $password);

        if( $this->updateUserPasswordByUsernameAndPassword($username, $password) )
        {
            if( $redirect !== NULL )
            {
                new Redirect($redirect);
            }

            return $this->setSuccessMessage('updateProcessSuccess');
        }

        return $this->setErrorMessage('forgotPasswordError');
    }

    /**
     * Protected encryption return link
     */
    protected function encryptionReturnLink($returnLinkPath, $username, $newEncodePassword)
    {
        return Base::suffix($returnLinkPath) . base64_encode($username) . '/' . base64_encode($newEncodePassword);
    }

    /**
     * Protected decryption return link
     */
    protected function decryptionReturnLink(&$username, &$password)
    {
        $username = base64_decode(URI::segment(-2));
        $password = base64_decode(URI::segment(-1));
    }

    /**
     * Protected get user data row by email
     */
    protected function getUserDataRowByEmail($email)
    {
        if( ! empty($this->emailColumn) )
        {
            $this->dbClass->where($this->emailColumn, $email);
        }
        else
        {
            $this->dbClass->where($this->usernameColumn, $email);
        }

        return $this->dbClass->get($this->tableName)->row();
    }

    /**
     * Protected create random new password
     */
    protected function createRandomNewPassword()
    {
        return Encode\RandomPassword::create(10);
    }

    /**
     * Protected send forgot password email
     */
    protected function sendForgotPasswordEmail($receiver, $message)
    {
        return Singleton::class('ZN\Email\Sender')
                        ->sender($this->senderMail, $this->senderName)
                        ->receiver($receiver, $receiver)
                        ->subject($this->getLang['newYourPassword'])
                        ->content($message)
                        ->send();
    }

    /**
     * Protected update user password
     */
    protected function updateUserPassword($email, $password)
    {
        if( ! empty($this->emailColumn) )
        {
            $this->dbClass->where($this->emailColumn, $email, 'and');
        }
        else
        {
            $this->dbClass->where($this->usernameColumn, $email, 'and');
        }

        return $this->dbClass->update($this->tableName, [$this->passwordColumn => $password]);
    }

    /**
     * Protected update user password with username
     */
    protected function updateUserPasswordByUsernameAndPassword($username, $newPassword)
    {
        return $this->dbClass->where($this->usernameColumn, $username)->update($this->tableName, [$this->passwordColumn => $newPassword]);
    }

    /**
     * Protected control properties parameters
     */
    protected function controlPropertiesParameters(&$email, &$verification, &$returnLinkPath, &$changePassword)
    {
        $email          = Properties::$parameters['email']          ?? $email;
        $verification   = Properties::$parameters['verification']   ?? NULL;
        $returnLinkPath = Properties::$parameters['returnLink']     ?? $returnLinkPath;
        $changePassword = Properties::$parameters['changePassword'] ?? $changePassword;

        Properties::$parameters = [];
    }
}
