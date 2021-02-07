<?php namespace ZN\Email\Drivers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Config;
use ZN\Support;
use ZN\DateTime\Date;
use ZN\Email\DriverMappingAbstract;
use ZN\Email\Exception\IMAPConnectException;

class ImapDriver extends DriverMappingAbstract
{
    /**
     * Connect
     * 
     * @var object
     */
    protected $connect;

    /**
     * Host
     * 
     * @var string
     */
    protected $host;

    /**
     * Magic Constructor
     */
    public function __construct(Array $config = [])
    {
        Support::func('imap_mail');

        $this->connection($config);
    }

    /**
     * Magic Destructor
     */
    public function __destruct()
    {
        imap_close($this->connect);
    }
    
    /**
     * Send Email
     * 
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param mixed  $headers
     * @param mixed  $settings
     * 
     * @return bool
     */
    public function send($to, $subject, $message, $headers = NULL, $settings = NULL)
    {
        return imap_mail($to, $subject, $message, $headers);    
    }

    /**
     * New
     * 
     * @param array $config = []
     * 
     * @return self
     */
    public static function new(Array $config = [])
    {
        return new self($config);
    }

    /**
     * Mail
     * 
     * @param int $mailId
     * 
     * @return object
     */
    public function mail(Int $mailId)
    {
        $overview  = imap_fetch_overview($this->connect, $mailId, FT_UID);
        $structure = imap_fetchstructure($this->connect, $mailId, FT_UID);
        $header    = imap_headerinfo($this->connect, $overview[0]->msgno);

        $from = $header->from[0]->mailbox . '@' . $header->from[0]->host;

        $section = 1;

        if( isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[0]) ) 
        {
            $part = $structure->parts[0];

            if( $part->subtype === 'ALTERNATIVE' )
            {
                $section = 1.1;
            }
        }

        $message = $this->contentDecoder($part->encoding ?? $structure->encoding, imap_fetchbody($this->connect, $mailId, $section, FT_UID));

        return (object)
        [
            'subject'       => utf8_decode(imap_utf8($overview[0]->subject)),
            'body'          => imap_qprint($message),
            'from'          => $this->cc($header->from ?? NULL),
            'replyTo'       => $this->cc($header->reply_to ?? NULL),
            'cc'            => $this->cc($header->cc ?? NULL),
            'bcc'           => $this->cc($header->bcc ?? NULL),
            'date'          => (new Date)->convert($header->Date, 'Y-m-d H:i:s'),
            'attachments'   => $this->attachments($structure->parts ?? NULL, $mailId)
        ];
    }

    /**
     * List
     * 
     * @param string $flag = 'all'
     * 
     * @return array
     */
    public function list(String $flag = 'all') : Array
    {
        return imap_search($this->connect, strtoupper($flag), SE_UID);
    }

    /**
     * Delete
     * 
     * @param scalar $mailId
     * 
     * @return bool
     */
    public function delete($mailId) : Bool
    {
        $return = imap_delete($this->connect, $mailId, FT_UID);

        imap_expunge($this->connect);

        return $return;
    }

    /**
     * Move
     * 
     * @param scalar $mailId
     * @param string $folder = 'Spam'
     * 
     * @return bool
     */
    public function move($mailId, String $folder = 'Spam') : Bool
    {
        if( ! in_array($folder, ['Spam', 'Trash', 'Drafts', 'Sent']) )
        {
            $folder = imap_utf7_encode($folder);
        }

        $return = imap_mail_move($this->connect, $mailId, $folder, CP_UID);

        imap_expunge($this->connect);

        return $return;
    }

    /**
     * Create Folder
     * 
     * @param string $folder
     * 
     * @return bool
     */
    public function createFolder(String $folder) : Bool
    {
        return imap_createmailbox($this->connect, $this->utf7FolderEncoder($folder));
    }

    /**
     * Delete Folder
     * 
     * @param string $folder
     * 
     * @return bool
     */
    public function deleteFolder(String $folder) : Bool
    {
        return imap_deletemailbox($this->connect, $this->utf7FolderEncoder($folder));
    }

    /**
     * Rename Folder
     * 
     * @param string $folder
     * 
     * @return bool
     */
    public function renameFolder(String $name, String $newName) : Bool
    {
        $host = '{' . $this->host . '}';

        return imap_renamemailbox($this->connect, $this->utf7FolderEncoder($name), $this->utf7FolderEncoder($newName));
    }
    
    /**
     * Change Status
     * 
     * @param scalar $mailId
     * @param string $status
     * 
     * @return bool
     */
    public function changeStatus($mailId, String $status) : Bool
    {
        $status = ucfirst(strtolower($status));

        if( in_array($status, ['Unread', 'Unseen']) )
        {
            return imap_clearflag_full($this->connect, $mailId, '\\Seen', ST_UID);
        }
        
        if( $status === 'Read' )
        {
            $status = 'Seen';
        }

        return imap_setflag_full($this->connect, $mailId, '\\' . $status, ST_UID);
    }

    public function error() : String
    {
        return imap_last_error();
    }

    /**
     * Protected Utf7 Folder Encoder
     */
    protected function utf7FolderEncoder($folder) : String
    {
        return imap_utf7_encode('{' . $this->host . '}' . $folder);
    }

    /**
     * Protected connection
     */
    protected function connection(Array $config = [])
    {
        if( empty($config) )
        {
            $config = Config::default('ZN\Email\EmailDefaultConfiguration')::get('Services', 'email')['imap'];
        }

        $host     = $this->host = $config['host'];
        $user     = $config['user'];
        $password = $config['password'];   
        $port     = ':' . ($config['port'] ?? '993');
        $flags    = '/' . implode('/' , $config['flags']);
        $mailbox  = $config['mailbox'] ?? 'INBOX';

        $connect = '{' . $host . $port . $flags . '}' . $mailbox;

        if( ! $host )
        {
            throw new IMAPConnectException;
        } 

        $this->connect = imap_open($connect, $user, $password);

        return $this;
    }

    /**
     * Protected attachments
     */
    protected function attachments($parts, $mailId)
    {
        $attachments = [];

        if( $parts ) foreach( $parts as $key => $part )
        {
            $body = imap_fetchbody($this->connect, $mailId, $key + 1, FT_UID);

            $body = $this->contentDecoder($part->encoding, $body);
            
            if( $body && ! empty($part->dparameters) )
            {
                $attachments[] =
                [
                    'file'    => $part->dparameters[0]->value,
                    'content' => $body
                ];
            }   
        }

        return $attachments;
    }

    /**
     * Protected content decoder
     */
    protected function contentDecoder($encoding, $body)
    {
        switch( $encoding )
        {
            case 1 : $body = imap_8bit($body)  ; break;
            case 2 : $body = imap_binary($body); break;
            case 3 : $body = imap_base64($body); break;
            default: $body = imap_qprint($body);
        }

        return $body;
    }

    /**
     * Protected cc
     */
    protected function cc($cc)
    {
        $addresses = [];
        
        if( is_array($cc) )
        {       
            foreach( $cc as $c )
            {
                $addresses[] = 
                [
                    'mail' => $c->mailbox . '@' . $c->host,
                    'name' => iconv_mime_decode($c->personal ?? '')
                ];
            }
        }

        return $addresses;
    }
}