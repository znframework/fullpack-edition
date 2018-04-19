<?php namespace ZN\Request;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\In;
use ZN\IS;
use ZN\Lang;
use ZN\Base;
use ZN\Config;
use ZN\Request;
use ZN\Security;
use ZN\DataTypes\Arrays;
use ZN\DataTypes\Strings;

class URI implements URIInterface
{
    /**
     * Magic Call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return string|int
     */
    public function __call($method, $parameters)
    {
        if( preg_match('/^(e|s)[0-9]+$/', $method) )
        {
            $typ = $method[0];
            $num = substr($method, 1);
            $val = $typ === 's' ? $num : -($num);
        
            return self::segment($val, ...$parameters);
        }

        return self::get($method, ...$parameters);
    }

    /**
     * Manipulation
     * 
     * @param array $rules
     * @param string $type = 'none' - options[none|left|right|both]
     * 
     * @return string
     */
    public static function manipulation(Array $rules, String $type = 'none') : String
    {
        $query = NULL;

        foreach( $rules as $key => $value )
        {
            if( is_numeric($key) )
            {
                if( ! empty($val = self::get($value)) )
                {
                    $query .= $value . '/' . $val . '/';
                }
            }
            else
            {
                $query .= $key . '/' . $value . '/';
            }
        }

        return self::_addFix($query, $type);
    }

    /**
     * Build Query
     * 
     * @param array  $data
     * @param string $separator = '/'
     * @param string $type = 'none' - options[none|left|right|both]
     * 
     * @return string
     */
    public static function buildQuery(Array $data, String $separator = '/', String $type = 'none') : String
    {
        $query = NULL;

        foreach( $data as $key => $value )
        {
            if( is_numeric($key) )
            {
                $query .= $value . '/';
            }
            else
            {
                $query .= $key . '/' . $value . '/';
            }
        }

        return self::_addFix($query, $type);
    }

    /**
     * Get
     * 
     * @param string|int $get   = 1
     * @param string|int $index = 1
     * @param bool       $while = false
     * 
     * @return string
     */
    public static function get($get = 1, $index = 1, Bool $while = false) : String
    {
        if( ! IS::char($index) )
        {
            $index = 1;
        }

        if( ! is_scalar($while) )
        {
            $while = false;
        }

        $segArr = self::segmentArray();
        $segVal = '';

        if( is_numeric($get) )
        {
            return self::getByIndex($get, $index);
        }

        if( in_array($get, $segArr) )
        {
            $segVal = array_search($get, $segArr);

            # 3. parameter is not empty
            # 2. The non-numerical state of the parameter
            if( ! empty($while) && ! is_numeric($index) )
            {
                return self::getByName($get, $index);
            }

            # 2. the parameter is all-in
            # It gives all segments from parameter 1.
            if( $index === 'all' )
            {
                return self::getNameAll($get);
            }

             # 3. parameter is not empty
            if( ! empty($while) )
            {
                $return = '';

                $countSegArr = count($segArr) - 1;

                if( $index > $countSegArr )
                {
                    $index = $countSegArr;
                }

                if( $index < 0 )
                {
                    $index = $countSegArr + $index + 1;
                }

                for( $i = 1; $i <= $index; $i++ )
                {
                    $return .= $segArr[$segVal + $i]."/";
                }

                $return = substr($return,0,-1);

                return $return;
            }

            # 2. the parameter is all-in
            # It gives all segments from parameter 1.
            if( $index === "count" )
            {
                return self::getNameCount($get);
            }

            if( isset($segArr[$segVal + $index]) )
            {
                return $segArr[$segVal + $index];
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns the number of segments after the specified segment.
     * 
     * @param string $get
     * 
     * @return int
     */
    public static function getNameCount(String $get) : Int
    {
        $segArr = self::segmentArray();

        if( in_array($get, $segArr) )
        {
            $segVal = array_search($get, $segArr);

            return count($segArr) - 1 - $segVal;
        }

        return false;
    }

    /**
     * Returns all segments after the specified segment.
     * 
     * @param string $get
     * 
     * @return string
     */
    public static function getNameAll(String $get) : String
    {
        $segArr = self::segmentArray();

        if( in_array($get, $segArr) )
        {
            $return = '';

            $segVal = array_search($get, $segArr);

            for( $i = 1; $i < count($segArr) - $segVal; $i++ )
            {
                $return .= $segArr[$segVal + $i]."/";
            }

            $return = substr($return, 0, -1);

            return $return;
        }

        return false;
    }

    /**
     * Used to get the range according to the index of the specified segment.
     * 
     * @param int $get   = 1
     * @param int $index = 1
     * 
     * @return string
     */
    public static function getByIndex(Int $get = 1, Int $index = 1) : String
    {
        $segArr = self::segmentArray();

        if( $get == 0 )
        {
            $get = 1;
        }

        $get -= 1;
        $uri  = '';

        $countSegArr = count($segArr);

        if( $index < 0 )
        {
            $index = $countSegArr + $index + 1;
        }

        if( $index > 0 )
        {
            $index = $get + $index;
        }

        if( abs($index) > $countSegArr )
        {
            $index = $countSegArr;
        }

        for( $i = $get; $i < $index; $i++ )
        {
            $uri .= $segArr[$i].'/';
        }

        return rtrim($uri, '/');
    }

    /**
     * Used to get the range according to the specified segment names.
     * 
     * @param string $get
     * @param mixed  $index = NULL
     * 
     * @return string
     */
    public static function getByName(String $get, $index = NULL) : String
    {
        $segArr = self::segmentArray();
        
        $getVal = (int) array_search($get, $segArr);

        if( $index === 'all' )
        {
            $indexVal = count($segArr) - 1;
        }
        else
        {
            $indexVal = array_search($index, $segArr);
        }

        $return = '';

        for( $i = $getVal; $i <= $indexVal; $i++ )
        {
            $return .= $segArr[$i]."/";
        }

        return substr($return, 0, -1);
    }

    /**
     * Get Segment Array
     * 
     * @return array
     */
    public static function segmentArray() : Array
    {
        $segmentEx = Arrays\RemoveElement::element(explode('/', self::_cleanPath()), '');

        return $segmentEx;
    }

    /**
     * Get Total Segments
     * 
     * @param int
     */
    public static function totalSegments() : Int
    {
        $segmentEx     = array_diff(self::segmentArray(), ["", " "]);
        $totalSegments = count($segmentEx);

        return $totalSegments;
    }

    /**
     * Get Segment Count / Get Total Segments
     * 
     * @return int
     */
    public static function segmentCount() : Int
    {
        return self::totalSegments();
    }

    /**
     * Get segment
     * 
     * @param int $set = 1
     * 
     * @return string
     */
    public static function segment(Int $seg = 1) : String
    {
        $segments = self::segmentArray();

        if( $seg > 0 )
        {
            $seg -= 1;
        }
        elseif( $seg < 0 )
        {
            $count = count($segments);
            $seg   = $count + $seg;
        }

        $select = $segments[$seg] ?? false;

        if( ! empty($select) )
        {
            return $segments[$seg];
        }
        elseif( $select === '0' ) // 5.3.34[added]
        {
            return (int) $select;
        }

        return false;
    }

    /**
     * Current Segment
     * 
     * @return string
     */
    public static function currentSegment() : String
    {
        return self::current(false);
    }

    /**
     * Current
     * 
     * @param bool $isPath = true
     * 
     * @return string
     */
    public static function current(Bool $isPath = true) : String
    {
        $currentPagePath = str_replace(Lang::get().'/', '', Base::currentPath());

        if( ($currentPagePath[0] ?? NULL) === '/' )
        {
            $currentPagePath = substr($currentPagePath, 1, strlen($currentPagePath) - 1);
        }

        if( $isPath === true )
        {
            return $currentPagePath;
        }
        else
        {
            $str = explode('/', $currentPagePath);

            if( count($str) > 1 )
            {
                return $str[count($str) - 1];
            }

            return $str[0];
        }
    }

    /**
     * Get active URL
     * 
     * @param bool $fullPath = false
     * 
     * @return string
     */
    public static function active(Bool $fullPath = false) : String
    {
        return Request::getActiveURL($fullPath);
    }

    /**
     * Base
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function base(String $uri = NULL) : String
    {
        return In::cleanInjection(BASE_DIR . $uri);
    }

    /**
     * Prev
     * 
     * @param bool $isPath = true
     * 
     * @return string
     */
    public static function prev(Bool $isPath = true) : String
    {
        if( ! isset($_SERVER['HTTP_REFERER']) )
        {
            return false;
        }
        
        $str = str_replace(URL::site(), '', $_SERVER['HTTP_REFERER']);

        if( $isPath === true )
        {
            return $str;
        }
        else
        {
            return Strings\Split::divide($str, '/', -1);
        }
    }

    /**
     * Protected Clean Path
     */
    protected static function _cleanPath()
    {
        $pathInfo = Security\Html::encode(self::active());

        return $pathInfo;
    }

    /**
     * Protected Add Fix
     */
    protected static function _addFix($query, $type)
    {
        $query = rtrim($query, '/');
        
        switch( $type )
        {
            case 'left'  : return Base::prefix($query);
            case 'right' : return Base::suffix($query);
            case 'both'  : return Base::presuffix($query);
            default      : return $query;
        }
    }
}
