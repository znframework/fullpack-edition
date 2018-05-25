<?php namespace ZN\Payment;

use ZN\Singleton;
use ZN\Request\URL;
use ZN\Services\CURL;

abstract class GatewayRequestAbstract
{
    /**
     * Protected settings
     * 
     * @var array
     */
    protected $settings = [];

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return $this
     */
    public function __call($method, $parameters)
    {
        if( isset($parameters[0]) )
        {
            $this->settings[$method] = $parameters[0];
        }
    
        return $this;
    }

    /**
     * Send request.
     */
    public function send(String $bank)
    {
        $this->missingInformation();
        
        $this->default();

        $_POST = $_POST + $this->settings;

        $curl = new CURL;

        if( ! isset($this->banks[$bank]) )
        {
            throw new Exception\InvalidBankException(NULL, $bank);
        }

        $curl = new CURL;

        return $curl->init($this->banks[$bank])
                    ->post(true)
                    ->postfields(URL::buildQuery($_POST))
                    ->returntransfer(true)
                    ->sslVerifypeer(false)
                    ->exec();
    }

    /**
     * Protected signature encoder
     */
    protected function signatureEncoder($signature)
    {
        return base64_encode(pack('H*', sha1($signature)));
    }

    /**
     * Protected missing information
     */
    protected function missingInformation()
    {
        foreach( $this->required as $data => $value )
        {
            if( ! isset($this->settings[$data]) )
            {
                throw new MissingInformationExpception(NULL, $value);  
            }
        }
    }
}