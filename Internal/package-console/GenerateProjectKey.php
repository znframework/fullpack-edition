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

use ZN\Base;

/**
 * @command generate-project-key
 * @description generate-project-key [addional]
 */
class GenerateProjectKey
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * 
     * @return void
     */
    public function __construct($command)
    {   
        $contents         = file_get_contents($file = CONFIG_DIR . 'Project.php');
        $replaceContents  = preg_replace_callback('/(?<key>\'key\'\s*=>\s*)(?<data>.*?),/', function($data)
        {
            return $data['key'] . Base::presuffix(hash('ripemd320', uniqid($command ?? '')), '\'') . ',';

        }, $contents);

        $return = false;

        if( $contents !== $replaceContents )
        {
            $return = (bool) file_put_contents($file, $replaceContents);
        }

        new Result($return);
    }
}