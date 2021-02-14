<?php namespace ZN\Console;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

/**
 * @command environment
 * @description environment key value
 * 
 * @codeCoverageIgnore
 */
class Environment
{
    /**
     * Env
     * 
     * @var string
     */
    const env = 'env';

    /**
     * Magic constructor
     * 
     * @param string $command
     * @param array  $parameters
     * 
     * @return void
     */
    public function __construct($command, $parameters)
    {   
        $data = json_decode(file_get_contents(self::env) ?: '', true) ?: [];

        $data[$command] = $parameters[0] ?? '';

        file_put_contents(self::env, json_encode($data));
    }

    /**
     * Export environment variables
     * 
     */
    public static function export($key)
    {
        $data = json_decode(file_get_contents(self::env) ?: '', true) ?: [];

        return $data[$key] ?? '';
    }
}