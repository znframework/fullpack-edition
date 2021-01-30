<?php namespace ZN\Language;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 * @since   2011
 */

use ZN\Lang;
use ZN\Filesystem;

class Select extends MLExtends
{
    /**
     * Select word.
     * 
     * @param string $key     = NULL
     * @param mixed  $convert = NULL
     */
    public function do(String $key = NULL, $convert = NULL)
    {
        if( Properties::$select === NULL )
        {
            if( is_file($this->getFilePath()) )
            {
                $read   = file_get_contents($this->getFilePath());
            }

            if( is_file($this->externalLang) )
            {
                $eread  = file_get_contents($this->externalLang);
            }

            $read                = json_decode($read  ?? '', true);
            $eread               = json_decode($eread ?? '', true);
            Properties::$select  = array_merge((array) $eread, (array) $read);
        }
        
        if( $key === NULL )
        {
            return Properties::$select;
        }

        if( isset(Properties::$select[$key]) )
        {
            if( is_array($convert) )
            {
                $return = str_replace(array_keys($convert), array_values($convert), Properties::$select[$key]);
            }
            else
            {
                $return = str_replace('%', $convert, Properties::$select[$key]);
            }
        }
        else
        {
            $return = $key;
        }

        return $return;
    }

    /**
     * Select all languages
     * 
     * @param mixed $app = NULL
     * 
     * @return array
     */
    public function all($app = NULL) : Array
    {
        if( ! is_string($app) )
        {
            if( $app === NULL )
            {
                $MLFiles = $this->getMLFiles();
            }
            elseif( is_array($app) )
            {
                $MLFiles = $app;
            }
            else
            {
                return false;
            }

            $allMLFiles = [];

            if( ! empty($MLFiles) ) foreach( $MLFiles as $file )
            {
                $removeExtension = $this->removeExtension($file);
                $allMLFiles[$removeExtension] = $this->all($removeExtension);
            }

            return $allMLFiles;
        }
        else
        {
            if( is_file($createFile = $this->_langFile($app)) )
            {
                $read = file_get_contents($createFile);

                return json_decode($read, true);
            }   

            return [];
        }
    }

    /**
     * Returns the keys and values of the selected language from the object type.
     * 
     * @return object 
     */
    public function keys()
    {
        return (object) $this->all(Lang::get());
    }

    /**
     * Get langs
     * 
     * @return array
     */
    public function langs()
    {
        $langs = [];
        $files = $this->getMLFiles();

        foreach( $files as $file )
        {
            $langs[] = $this->removeExtension($file);
        }
    
        return $langs;
    }

    /**
     * Protected get ml files
     */
    protected function getMLFiles()
    {
        return Filesystem::getFiles($this->appdir, 'ml');
    }

    /**
     * Protected remove extension
     */
    protected function removeExtension($file)
    {
        return str_replace($this->extension, '', $file);
    }
}
