<?php namespace ZN\XML;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use stdClass;
use SimpleXMLElement;

class Parser
{
    /**
     * Parses an XML document.
     * 
     * @param string $xml 
     * @param string $result = 'object'
     * 
     * @return mixed
     */
    public static function do(String $xml, String $result = 'object')
    {
        $xml    = self::cleanXMLElement($xml);
        $parser = xml_parser_create();

        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $xml, $tags);
        xml_parser_free($parser);

        $elements = [];
        $stack    = [];

        if( ! empty($tags) ) foreach( $tags as $tag )
        {

            $index = count($elements);

            if( $tag['type'] === 'complete' || $tag['type'] === 'open' )
            {
                if( $result === 'object' )
                {
                    $elements[$index]          = new stdClass;
                    $elements[$index]->name    = $tag['tag']        ?? NULL;
                    $elements[$index]->content = $tag['value']      ?? NULL;
                    $elements[$index]->attr    = $tag['attributes'] ?? NULL;

                    if( $tag['type'] === 'open' )
                    {
                        $elements[$index]->child = [];
                        $stack[count($stack)]    = &$elements;
                        $elements                = &$elements[$index]->child;
                    }
                }
                else
                {
                    $elements[$index]            = [];
                    $elements[$index]['name']    = $tag['tag']        ?? NULL;
                    $elements[$index]['content'] = $tag['value']      ?? NULL;
                    $elements[$index]['attr']    = $tag['attributes'] ?? NULL;

                    if( $tag['type'] === 'open' )
                    {
                        $elements[$index]['child'] = [];
                        $stack[count($stack)]      = &$elements;
                        $elements                  = &$elements[$index]['child'];
                    }
                }
            }

            if( $tag['type'] === 'close' )
            {
                $elements = &$stack[count($stack) - 1];
                unset($stack[count($stack) - 1]);
            }
        }

        return $elements[0] ?? [];
    }

    /**
     * Parses an XML document.
     * 
     * @param string $xml 
     * 
     * @return array
     */
    public static function array(String $data) : Array
    {
        return self::do($data, 'array');
    }

    /**
     * Parses an XML document.
     * 
     * @param string $xml 
     * 
     * @return string
     */
    public static function json(String $data) : String
    {
        return json_encode(self::do($data, 'array'));
    }

    /**
     * Parses an XML document.
     * 
     * @param string $xml 
     * 
     * @return object
     */
    public static function object(String $data)
    {
        return self::do($data, 'object');
    }

    /**
     * Simple XML Element
     * 
     * @param string $data
     * @param int    $option = 0
     * 
     * @return SimpleXMLElement
     */
    public static function simple(String $data, Int $option = 0)
    {
        $data = self::cleanXMLElement($data);

        return new SimpleXMLElement($data, $option);
    }

    /**
     * Simple URL XML Element
     * 
     * @param string $data
     * @param int    $option = 0
     * 
     * @return SimpleXMLElement
     */
    public static function simpleURL(String $data, Int $option = 0)
    {
        return new SimpleXMLElement($data, $option, true);
    }

    /**
     * Private clean XML element
     */
    private static function cleanXMLElement($xml)
    {
        return preg_replace('/<\?xml(.*?)>/', '', $xml);
    }
}
