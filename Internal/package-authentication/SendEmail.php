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
use ZN\Singleton;

class SendEmail extends UserExtends
{
    /**
     * Magic construct
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->emailClass = Singleton::class('ZN\Email\Sender');
    }
    
    /**
     * Attachment
     * 
     * @param string $file
     * @param string $disposition = NULL
     * @param string $newName     = NULL
     * @param mixed  $mime        = NULL
     * 
     * @return SendEmail
     */
    public function attachment(String $file, String $disposition = NULL, String $newName = NULL, $mime = NULL)
    {
        $this->emailClass->attachment($file, $disposition, $newName, $mime);

        return $this;
    }

    /**
     * Send
     * 
     * @param string $subject
     * @param string $body
     * @param int    $count = 35
     * 
     * @return void
     */
    public function send(String $subject, String $body, Int $count = 35)
	{
        if( empty($this->usernameColumn) )
        {
            return false;
        }
         
		$users     = array_chunk($this->getUserDataResult(), $count);
		$sendCount = count($users);

        $this->emailClass->sender($this->senderMail, $this->senderName);

		for( $i = 0; $i < $sendCount; $i++ )
		{
			foreach( $users[$i] as $user )
			{
                $username = $user->{$this->usernameColumn};

                $email = IS::email($username)
                       ? $username
                       : ($user->{$this->emailColumn} ?? NULL);

                if( IS::email($email) )
                {
                    $this->emailClass->bcc($email, $username);
                }
			}

            $this->emailClass->send($subject, $body);
		}
    }
    
    /**
     * Protected get user data result
     */
    protected function getUserDataResult()
    {
        if( ! empty($this->bannedColumn) )
        {
        	$this->dbClass->where($this->bannedColumn, 0);
        }
        
        return $this->dbClass->get($this->tableName)->result();  
    }
}
