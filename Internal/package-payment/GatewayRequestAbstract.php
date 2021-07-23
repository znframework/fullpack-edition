<?php namespace ZN\Payment;

use ZN\Singleton;
use ZN\DateTime\Date;

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
     * 
     * @param string $type;
     */
    abstract public function send(string $type);

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
                throw new Exception\MissingInformationExpception(NULL, $value);  
            }
        }
    }

    /**
     * Protected year month converter
     */
    protected function yearAndMonthConverter(&$year, &$month)
    {
        $date   = new Date;
        $date   = $date->convert($year . '-' . $month . '-01', '{sy}/{mn0}');
        $dateEx = explode('/', $date);
        $year   = $dateEx[0] ?? $year;
        $month  = $dateEx[1] ?? $month;
    }
}