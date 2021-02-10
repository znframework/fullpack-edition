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

use ZN\Lang;
use ZN\Config;
use ZN\Inclusion;
use ZN\Singleton;
use ZN\Request\Method;
use ZN\Cryptography\Encode;

class UserExtends
{
    /**
     * Get user config
     * 
     * @var array
     */
    protected $getConfig;

    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        # If no configuration file is found, predefined settings will be enabled.
        $this->getConfig  = array_merge
        (
            Config::default('ZN\Authentication\AuthenticationDefaultConfiguration')::get('Authentication') ?: [],
            Config::get('Auth') ?: []
        );
        
        # When the user is registered in, 
        # the algorithm to encrypt the password is set.
        $this->encodeType = $this->getConfig['encode'];

        # Spectator for users profile
        $this->spectator = $this->getConfig['spectator'] ?: NULL;

        # Table name where user information will be stored.
        $this->tableName  = $this->getConfig['matching']['table'];

        # The User class contains matching information for column 
        # information that is required for some operations.
        $matchingColumn = $this->getConfig['matching']['columns'];

        $this->usernameColumn     = $matchingColumn['username'];
        $this->passwordColumn     = $matchingColumn['password'];
        $this->emailColumn        = $matchingColumn['email'];
        $this->bannedColumn       = $matchingColumn['banned'];
        $this->activeColumn       = $matchingColumn['active'];
        $this->activationColumn   = $matchingColumn['activation'];
        $this->bannedColumn       = $matchingColumn['banned'];
        $this->verificationColumn = $matchingColumn['verification'];  
        $this->otherLoginColumns  = $matchingColumn['otherLogin'];     

        # If the user's information is stored in more than one tablature, 
        # this table is used so that it can be accessed by the User class.
        $joining = $this->getConfig['joining'];

        $this->joinTables = $joining['tables'];
        $this->joinColumn = $joining['column'];

        # It contains pre-defined e-mail and name information 
        # for user class's e-mail sending methods.
        $emailSenderInfo = $this->getConfig['emailSenderInfo'];

        $this->senderMail = $emailSenderInfo['mail'];
        $this->senderName = $emailSenderInfo['name'];
        
        # If no language file is found, predefined settings will be enabled.
        $this->getLang = Lang::default('ZN\Authentication\AuthenticationDefaultLanguage')
                             ::select('Authentication');

        # PThe necessary classes are called for the User class.
        $this->dbClass      = Singleton::class('ZN\Database\DB');
        $this->sessionClass = Singleton::class('ZN\Storage\Session');
        $this->cookieClass  = Singleton::class('ZN\Storage\Cookie');
    }

    /**
     * Get unique username key
     * 
     * @return string
     */
    public function getUniqueUsernameKey()
    {
        return CONTAINER_PROJECT . $this->usernameColumn;
    }

    /**
     * Get unique password key
     * 
     * @return string
     */
    public function getUniquePasswordKey()
    {
        return CONTAINER_PROJECT . $this->passwordColumn;
    }

    /**
     * Set column 
     * 
     * @param string $column
     * @param mixed  $value
     */
    public function column(String $column, $value)
    {
        Properties::$parameters['column'][$column] = $value;
    }

    /**
     * Return link
     * 
     * @param string $returnLink
     */
    public function returnLink(String $returnLink)
    {
        Properties::$parameters['returnLink'] = $returnLink;
    }

    /**
     * Get encryption password
     * 
     * @param string $password
     * 
     * @return string
     */
    public function getEncryptionPassword($password)
    {
        return ! empty($this->encodeType) ? Encode\Type::create($password ?? '', $this->encodeType) : $password;
    }

    /**
     * Sets activation email
     * 
     * 5.7.3[added]
     * 
     * @param string $message
     */
    public function setEmailTemplate(String $message)
    {
        Properties::$setEmailTemplate = $message;
    }

    /**
     * Protected activation email data
     */
    protected function replaceActivationEmailData(Array $replace)
    {
        $data = Properties::$setEmailTemplate;

        Properties::$setEmailTemplate = NULL;

        $preg = 
        [
            '/\{user\}/' => $replace['user'],
            '/\{pass\}/' => $replace['pass'],
            '/\{url\}/'  => $replace['url']
        ];

		return preg_replace_callback('/\[(.*?)\]/', function($match) use($replace)
		{
			return $replace['url'] . $match[1];
			
		}, preg_replace(array_keys($preg), array_values($preg), $data));
    }

    /**
     * Protected get email template
     */
    protected function getEmailTemplate($data, $template)
    {
        # 5.7.3[added]
        # Sets activation email content
        if( ! empty(Properties::$setEmailTemplate) )
        {
            return $this->replaceActivationEmailData($data);
        }
        
        # Default activation email template
        return Inclusion\View::use($template, $data, true, __DIR__ . '/Resources/');
    }

    /**
     * Protected auto match columns
     */
    protected function autoMatchColumns(&$data)
    {
        if( is_string($data) && in_array($data, ['post', 'get', 'request']) )
        {
            $columns = array_flip($this->getUserTableColumns());
            $data    = array_intersect_key(Method::$data(), $columns);
        }
    }

    /**
     * Protected get user table columns
     */
    protected function getUserTableColumns()
    {
        return $this->dbClass->get($this->tableName)->columns();
    }

    /**
     * Get user table by username
     */
    protected function getUserTableByUsername($username)
    {
        return $this->dbClass->where($this->usernameColumn, $username)->get($this->tableName);
    }

    /**
     * Protected set error message
     */
    protected function setErrorMessage($string)
    {
        return ! (bool) (Properties::$error = $this->getLang[$string]);
    }

    /**
     * Protected set error message
     */
    protected function setSuccessMessage($string)
    {
        return (bool) (Properties::$success = $this->getLang[$string]);
    }

    /**
     * protected multi username columns
     * 
     * @param string $value
     * 
     * @return void
     */
    protected function _multiUsernameColumns($value)
    {
        if( ! empty($this->otherLoginColumns) )
        {
            foreach( $this->otherLoginColumns as $column )
            {
                $this->dbClass->where($column, $value, 'or');
            }
        }
    }
}
