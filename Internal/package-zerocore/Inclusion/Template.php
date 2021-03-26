<?php namespace ZN\Inclusion;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Inclusion\Exception\FileNotFoundException;

class Template
{
    /**
     * Import Template
     * 
     * @param string $page
     * @param array  $data          = NULL
     * @param bool   $obGetContents = false
     * 
     * @return mixed
     * 
     * @codeCoverageIgnore
     */
    public static function use(string $page, array $data = NULL, bool $obGetContents = false)
    {
        if( $return = View::use($page, $data, $obGetContents, TEMPLATES_DIR) )
        {
            return $return;
        }
        elseif( $return = View::use($page, $data, $obGetContents, EXTERNAL_TEMPLATES_DIR) )
        {
            return $return;
        }
        else
        {
            return false;
        }
    }
}
