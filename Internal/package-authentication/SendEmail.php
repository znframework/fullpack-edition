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
     * Keeps email class
     * 
     * @var object
     */
    protected $emailClass;

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
     */
    public function attachment(string $file, string $disposition = NULL, string $newName = NULL, $mime = NULL)
    {
        $this->emailClass->attachment($file, $disposition, $newName, $mime);
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
    public function send(string $subject, string $body, int $count = 35)
	{
        if( empty($this->usernameColumn) )
        {
            return false; // @codeCoverageIgnore
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
                       : ($user->{$this->emailColumn} ?? '');

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
        	$this->dbClass->where($this->bannedColumn, 0); // @codeCoverageIgnore
        }
        
        return $this->dbClass->get($this->tableName)->result();  
    }
}
