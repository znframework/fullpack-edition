<?php namespace ZN\Payment;

use ZN\Singleton;

class Gateway
{
    /**
     * Get gateway request class
     * 
     * @param string $gateway
     * 
     * @return object
     */
    public static function request(String $gateway)
    {
        return Singleton::class(self::getClassName($gateway, 'Request'));
    }

    /**
     * Get gateway reponse class
     * 
     * @param string $gateway
     * 
     * @return object
     */
    public static function response(String $gateway)
    {
        return Singleton::class(self::getClassName($gateway, 'Response'));
    }

    /**
     * Protected get class name
     */
    protected static function getClassName($gateway, $type)
    {
        return 'ZN\Payment\\' . $gateway . '\\' . $type;
    }
}