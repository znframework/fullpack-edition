<?php namespace ZN\Services;
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
use ZN\Protection\Json;

abstract class CDNAbstract
{  
    /**
     * Json file.
     * 
     * @var string
     */
    protected $jsonFile = FILES_DIR . 'CDNLinks.json';

    /**
     * Refresh request api.
     * 
     * @var bool
     */
    protected $refresh = false;

    /**
     * Refresh request api.
     */
    public function refresh()
    {
        $this->refresh = true;
    }

    /**
     * Set json file path.
     * 
     * @param string $jsonFile
     */
    public function setJsonFile(String $jsonFile)
    {
        $this->jsonFile = $jsonFile;
    }

    /**
     * Gets links.
     * 
     * @return array
     */
    public function getLinks() : Array
    {
        if( ! $this->isJsonCheck() || $this->refresh === true )
        {
            $result = $this->getApiResult();

            if( ! is_object($result) )
            {
                throw new Exception\BadRequestURLException($this->address);
            }

            $this->putJsonContent($result);
        }

        $result = $this->decodeArrrayJsonContent();

        return $this->convertArrayByKeyValues($result);
    }

    /**
     * Get link
     * 
     * @param string $key
     * @param string $version = 'latest'
     * 
     * @return string|false
     */
    public function getLink(String $key, String $version = 'latest')
    {
        $return = $this->getLinks()[$key] ?? false;

        if( $version !== 'latest' )
        {
            return $this->versionModifier($version, $return);
        }

        return $return;
    }

    /**
     * Protected version modifier
     */
    protected function versionModifier($version, $return)
    {
        return preg_replace('/([0-9]\.*){2,3}/', $version, $return);
    }

    /**
     * Protected convert array by key values
     */
    protected function convertArrayByKeyValues($result)
    {
        $array = [];

        foreach( $result['results'] as $value )
        {
            $array[$value['name']] = $value['latest'];
        }

        return $array;
    }

    /**
     * Protected get api result
     */
    protected function getApiResult()
    {
        return Singleton::class('ZN\Services\Restful')->get($this->address);
    }

    /**
     * Protected decode json array content
     */
    protected function decodeArrrayJsonContent()
    {
        return json_decode($this->getJsonContent(), true);
    }

    /**
     * Protected get json content
     */
    protected function getJsonContent()
    {
        return file_get_contents($this->jsonFile);
    }

    /**
     * Protected put json content
     */
    protected function putJsonContent($result)
    {
        file_put_contents($this->jsonFile, json_encode($result));
    }

    /**
     * Protected is json check
     */
    protected function isJsonCheck()
    {
        return is_file($this->jsonFile) && Json::check($this->getJsonContent());
    }
}
