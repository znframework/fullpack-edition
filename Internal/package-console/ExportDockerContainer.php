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

use ZN\Filesystem;

/**
 * @command export-docker-container
 * @description export-docker-container version
 * 
 * @codeCoverageIgnore
 */
class ExportDockerContainer
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
        $directory = 'software-drivers/Docker/' . $command . '/';

        if( ! is_dir($directory) )
        {
            new Result($directory . ' not found!'); return;
        }

        $dockerFile        = file_get_contents($directory . 'Dockerfile');
        $dockerComposeFile = file_get_contents($directory . 'docker-compose.yml');

        if( file_put_contents('Dockerfile', $dockerFile) && file_put_contents('docker-compose.yml', $dockerComposeFile) )
        {
            Filesystem::deleteFolder('software-drivers');

            new Result(true); 
        }
        else
        {
            new Result(false);
        }
    }
}