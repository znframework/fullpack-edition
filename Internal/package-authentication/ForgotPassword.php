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
use ZN\Inclusion;
use ZN\Singleton;
use ZN\Request\URL;
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
     * Forgot Password
     * 
     * @param string $email          = NULL
     * @param string $returnLinkPath = NULL
     * 
     * @return bool
     */
    public function do(String $email = NULL, String $returnLinkPath = NULL) : Bool
    {
        $this->controlPropertiesParameters($email, $verification, $returnLinkPath);

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
            $templateData   = 
            [
                'usernameColumn' => $row->{$this->usernameColumn},
                'newPassword'    => $newPassword,
                'returnLinkPath' => $returnLinkPath
            ];

            if( $this->sendForgotPasswordEmail($email, $this->setForgotPasswordEmailBodyTemplate($templateData)) )
            {
                if( $this->updateUserPassword($email, $encodePassword) )
                {
                    return $this->setSuccessMessage('forgotPasswordSuccess');
                }

                return $this->setErrorMessage('updateError');
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
     * Protected set forgot passward email body template
     */
    protected function setForgotPasswordEmailBodyTemplate($data)
    {
        return Inclusion\View::use('ForgotPassword', $data, true, __DIR__ . '/Resources/');
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
     * Protected control properties parameters
     */
    protected function controlPropertiesParameters(&$email, &$verification, &$returnLinkPath)
    {
        $email          = Properties::$parameters['email']        ?? $email;
        $verification   = Properties::$parameters['verification'] ?? NULL;
        $returnLinkPath = Properties::$parameters['returnLink']   ?? $returnLinkPath;

        Properties::$parameters = [];
    }
}
