<?php namespace ZN\Image;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Watermark
{
    /**
     * Protected align
     */
    public static function align($type, $swidth, $sheight, $twidth, $theight, $margin)
    {
        switch( strtolower($type) )
        {
            case 'center':
            {
                $x = self::alignCenter($twidth , $swidth);
                $y = self::alignCenter($theight, $sheight);
            }
            break;
            case 'topleft':
            {
                $x = $margin;
                $y = $margin;
            }
            break;
            case 'topcenter':
            {
                $x = self::alignCenter($twidth , $swidth);
                $y = $margin;
            }
            break;
            case 'topright':
            {
                $x = self::alignEdge($twidth, $swidth, -$margin);
                $y = $margin;
            }
            break;
            case 'middleleft':
            {
                $x = $margin;
                $y = self::alignCenter($theight, $sheight);
            }
            break;
            case 'middleright':
            {
                $x = self::alignEdge($twidth, $swidth, -$margin);
                $y = self::alignCenter($theight, $sheight);
            }
            break;
            case 'bottomleft':
            {
                $x = $margin;
                $y = self::alignEdge($theight, $sheight, -$margin);
            }
            break;
            case 'bottomcenter':
            {
                $x = self::alignCenter($twidth, $swidth);
                $y = self::alignEdge($theight, $sheight, -$margin);
            }
            break;
            case 'bottomright':
            {
                $x = self::alignEdge($twidth , $swidth , -$margin);
                $y = self::alignEdge($theight, $sheight, -$margin);
            }
            break;
        }

        return [$x, $y];
    }

    /**
     * Protected align left
     */
    protected static function alignEdge($val1, $val2, $margin)
    {
        return $val1 - $val2 + $margin;
    }

    /**
     * Protected align center
     */
    protected static function alignCenter($val1, $val2)
    {
        return ($val1 / 2) - ($val2 / 2);
    }
}
