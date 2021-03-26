<?php namespace ZN\Payment\Nestpay;

use ZN\DataTypes\Arrays;

class Response
{
    /**
     * Protected exclude parameters
     * 
     * @var array
     */
    protected $parameters = ['AuthCode', 'Response', 'HostRefNum', 'ProcReturnCode', 'TransId', 'ErrMsg'];

    /**
     * Get parameters
     * 
     * @var array
     */
    public function parameters()
    {
        return Arrays\RemoveElement::use($_POST, NULL, $this->parameters);
    }

    /**
     * Is valid hash
     * 
     * @param string $storeKey
     * 
     * @return bool
     */
    public function isValidHash() : bool
    {
        $hash   = $_POST['HASHPARAMS'];
        $hashEx = explode(':', $hash);
        $real   = NULL;

        foreach( $hashEx as $part )
        {
            if( ! empty($_POST[$part]) )
            {
                $real .= $_POST[$part];
            }
        }

        return $real === $_POST['HASHPARAMSVAL'] && $_POST['HASH'] === base64_encode(pack('H*',sha1($real . $_POST['storeKey'])));
    }

    /**
     * Is 3d
     * 
     * @return bool
     */
    public function is3D() : bool
    {
        return in_array($_POST["mdStatus"], ['1','2','3','4']);
    }

    /**
     * Is approved
     * 
     * @return bool
     */
    public function isApproved() : bool
    {
        return empty($_POST["ErrMsg"]) && $_POST["Response"] === 'Approved';
    }

    /**
     * Get error messages
     * 
     * @return string|false
     */
    public function error() 
    {
        return $_POST["ErrMsg"] ?: false;
    }
}