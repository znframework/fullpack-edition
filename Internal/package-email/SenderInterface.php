<?php namespace ZN\Email;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface SenderInterface
{
    /**
     * Settings
     * 
     * @param array $settings = NULL
     * 
     * @param return Sender
     */
    public function settings(array $settings = NULL) : Sender;

    /**
     * Content Tyep
     * 
     * @param string $type = 'plain' - options[plain|html]
     * 
     * @return Sender
     */
    public function contentType(string $type = 'plain') : Sender;

    /**
     * Sets charset
     * 
     * @param string $charset
     * 
     * @return Sender
     */
    public function charset(string $charset = 'UTF-8') : Sender;

    /**
     * Sets priority
     * 
     * @param int count = 3
     * 
     * @return Sender
     */
    public function priority(int $count = 3) : Sender;

    /**
     * Add Header
     * 
     * @param string $header
     * @param string $value
     * 
     * @return Sender
     */
    public function addHeader(string $header, string $value) : Sender;

   /**
     * Sets Encoding Type
     * 
     * @param string $type = '8bit' - options[7bit|8bit]
     * 
     * @return Sender
     */
    public function encodingType(string $type = '8bit') : Sender;

    /**
     * Sets multipart
     * 
     * @param string $multiPart = 'related' - options[related|alternative|mixed]
     * 
     * @return Sender
     */
    public function multiPart(string $multiPart = 'related') : Sender;

    /**
     * Sets SMTP Host
     * 
     * @param string $host
     * 
     * @return Snder
     */
    public function smtpHost(string $host) : Sender;

    /**
     * Sets SMTP User
     * 
     * @param string $user
     * 
     * @return Sender
     */
    public function smtpUser(string $user) : Sender;

    /**
     * Sets SMTP DSN
     * 
     * @param bool $dsn = true
     * 
     * @return Sender
     */
    public function smtpDsn(bool $dsn = true) : Sender;

    /**
     * Sets SMTP Passowrd
     * 
     * @param string $pass
     * 
     * @return Sender
     */
    public function smtpPassword(string $pass) : Sender;

    /**
     * Sets SMTP Port
     * 
     * @param int port = 587
     * 
     * @param Sender
     */
    public function smtpPort(int $port = 587) : Sender;

    /**
     * Sets SMTP Timeout
     * 
     * @param int $timeout = 10
     * 
     * @return Sender
     */
    public function smtpTimeout(int $timeout = 10) : Sender;

    /**
     * Sets SMTP Keep Alive
     * 
     * @param bool $keepAlive = true
     * 
     * @return Sender
     */
    public function smtpKeepAlive(bool $keepAlive = true) : Sender;

    /**
     * Sets SMTP Encode
     * 
     * @param string $encode
     * 
     * @return Sender
     */
    public function smtpEncode(string $encode) : Sender;

    /**
     * To
     * 
     * @param mixed  $to
     * @param string $name = NULL
     * 
     * @return Sender
     */
    public function to($to, string $name) : Sender;

    /**
     * To / Receiver
     * 
     * @param mixed  $to
     * @param string $name = NULL
     * 
     * @return Sender
     */
    public function receiver($to, string $name) : Sender;

    /**
     * Reply To
     * 
     * @param mixed  $to
     * @param string $name = NULL
     * 
     * @return Sender
     */
    public function replyTo($replyTo, string $name) : Sender;


    /**
     * CC
     * 
     * @param mixed  $to
     * @param string $name = NULL
     * 
     * @return Sender
     */
    public function cc($cc, string $name) : Sender;

    /**
     * BCC
     * 
     * @param mixed  $to
     * @param string $name = NULL
     * 
     * @return Sender
     */
    public function bcc($bcc, string $name) : Sender;

    /**
     * From
     * 
     * @param string $from
     * @param string $name       = NULL
     * @param string $returnPath = NULL
     * 
     * @return Sender
     */
    public function from(string $from, string $name = NULL, string $returnPath = NULL) : Sender;

    /**
     * From / Sender
     * 
     * @param string $from
     * @param string $name       = NULL
     * @param string $returnPath = NULL
     * 
     * @return Sender
     */
    public function sender(string $from, string $name = NULL, string $returnPath = NULL) : Sender;

    /**
     * Subject
     * 
     * @param string $subject
     * 
     * @return Sender
     */
    public function subject(string $subject) : Sender;

    /**
     * Template
     * 
     * @param string $table
     * @param mixed  $column
     * @param array  $data
     * 
     * @return Sender
     */
    public function template(string $table, $column, array $data = []) : Sender;

    /**
     * Template Match
     * 
     * @param string $content
     * @param array  $data
     * 
     * @return string
     */
    public function templateMatch(string $content, array $data) : string;

    /**
     * Message
     * 
     * @param string $message
     * 
     * @return Sender
     */
    public function message(string $message) : Sender;

    /**
     * Message / Content
     * 
     * @param string $message
     * 
     * @return Sender
     */
    public function content(string $message) : Sender;

    /**
     * Attachment
     * 
     * @param string $file
     * @param string $disposition = NULL
     * @param string $newName     = NULL
     * @param mixed  $mime        = NULL
     * 
     * @return Sender
     */
    public function attachment(string $file, string $disposition = NULL, string $newName = NULL, $mime = NULL) : Sender;

    /**
     * Attachment Content ID
     * 
     * @param string $filename
     * 
     * @return mixed
     */
    public function attachmentContentId(string $filename);

    /**
     * Send
     * 
     * @param string $subject = NULL
     * @param string $message = NULL
     * 
     * @return bool
     */
    public function send(string $subject = NULL, string $message = NULL) : bool;
}
