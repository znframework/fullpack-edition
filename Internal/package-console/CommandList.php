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

use ZN\Singleton;
use ZN\Filesystem;

class CommandList
{
    /**
     * Magic constructor
     * 
     * @param 
     * 
     * @return void
     */
    public function __construct()
    {   
        $numbers  = [];
        $getFiles = Filesystem::getFiles(__DIR__);

        foreach( $getFiles as $file )
        {
            $class = __NAMESPACE__ . '\\' . Filesystem::removeExtension($file);
            
            if( class_exists($class) )
            {
                $annotation = Singleton::class('ZN\Helpers\Reflect')->annotation($class);

                if( isset($annotation->command) )
                {
                    $numbers[strlen($annotation->command)] = strlen($annotation->description);
                    $commands[$annotation->command] = $annotation->description;
                }    
            }   
        }

        $keys = array_keys($numbers); rsort($numbers); rsort($keys);

        $this->descriptionNameCount = $numbers[0];
        $this->commandNameCount     = $keys[0];

        $line = $this->line();

        $output  = $line;
        $output .= $this->title();
        $output .= $line;
        
        ksort($commands);

        foreach( $commands as $key => $command )
        {
            $output .= $this->command($key, $command);
        }

        $output .= $line;

        echo $output;
    }

    /**
     * Protected Line
     */
    protected function line()
    {
        return '+' . str_repeat('-', $this->commandNameCount + 2) . '+' . str_repeat('-', $this->descriptionNameCount + 2) . '+' . EOL;
    }

    /**
     * Protected Title
     */
    protected function title()
    {
        return '| Command' . str_repeat(' ', $this->commandNameCount - 6) . '| Description' . str_repeat(' ', $this->descriptionNameCount - 11) . ' |' . EOL;
    }

    /**
     * Protected Command
     */
    protected function command($key, $command)
    {
        return '| ' . $key . str_repeat(' ', $this->commandNameCount - strlen($key)) . ' | '. $command . str_repeat(' ', $this->descriptionNameCount - strlen($command)) . ' |' . EOL;
    }
}