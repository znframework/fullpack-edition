<?php namespace ZN\DataTypes;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Datatype;
use ZN\Controller\Factory;
use ZN\Ability\Functionalization;

class Arrays extends Factory
{
    use Functionalization;

    const factory =
    [
        'methods' =>
        [
            'casing'                       => 'Arrays\Casing::use',
            'lowercase'                    => 'Arrays\Casing::lower',
            'uppercase'                    => 'Arrays\Casing::upper',
            'titlecase'                    => 'Arrays\Casing::title',
            'lowerkeys'                    => 'Arrays\Casing::lowerKeys',
            'upperkeys'                    => 'Arrays\Casing::upperKeys',
            'titlekeys'                    => 'Arrays\Casing::titleKeys',
            'lowervalues'                  => 'Arrays\Casing::lowerValues',
            'uppervalues'                  => 'Arrays\Casing::upperValues',
            'titlevalues'                  => 'Arrays\Casing::titleValues',
            'getfirst'                     => 'Arrays\GetElement::first',
            'getlast'                      => 'Arrays\GetElement::last',
            'pick'                         => 'Arrays\GetElement::pick',
            'addfirst'                     => 'Arrays\AddElement::first',
            'addlast'                      => 'Arrays\AddElement::last',
            'removekey'                    => 'Arrays\RemoveElement::key',
            'removevalue'                  => 'Arrays\RemoveElement::value',
            'remove'                       => 'Arrays\RemoveElement::use',
            'removelast'                   => 'Arrays\RemoveElement::last',
            'removefirst'                  => 'Arrays\RemoveElement::first',
            'deleteelement'                => 'Arrays\RemoveElement::element',
            'delete'                       => 'Arrays\RemoveElement::element',
            'order'                        => 'Arrays\Sort::order',
            'sort'                         => 'Arrays\Sort::normal',
            'descending'                   => 'Arrays\Sort::descending',
            'ascending'                    => 'Arrays\Sort::ascending',
            'ascendingkey'                 => 'Arrays\Sort::ascendingKey',
            'descendingkey'                => 'Arrays\Sort::descendingKey',
            'naturalinsensitive'           => 'Arrays\Sort::insensitive',
            'natural'                      => 'Arrays\Sort::natural',
            'shuffle'                      => 'Arrays\Sort::shuffle',
            'reverse'                      => 'Arrays\Sort::reverse',
            'including'                    => 'Arrays\Including::use',
            'include'                      => 'Arrays\Including::use',
            'excluding'                    => 'Arrays\Excluding::use',
            'exclude'                      => 'Arrays\Excluding::use',
            'each'                         => 'Arrays\Each::use',
            'force'                        => 'Arrays\Force::do',
            'forcevalues'                  => 'Arrays\Force::values',
            'forcekeys'                    => 'Arrays\Force::keys',
            'forcerecursive'               => 'Arrays\Force::recursive',
            'keyval'                       => 'Arrays\Element::use',
            'element'                      => 'Arrays\Element::use',
            'unidimensional'               => 'Arrays\Unidimensional::do',
            'flatten'                      => 'Arrays\Unidimensional::do',
            'searchbetween'                => 'Arrays\Search::between',
            'searchbetweenboth'            => 'Arrays\Search::betweenBoth',
            'searchkeybetween'             => 'Arrays\Search::betweenWithKey',
            'searchkeybetweenboth'         => 'Arrays\Search::betweenBothWithKey',
            'searchkeytovaluebetween'      => 'Arrays\Search::betweenWithKeyToValue',
            'searchvaluetokeybetween'      => 'Arrays\Search::betweenWithValueToKey',
            'searchkeytovaluebetweenboth'  => 'Arrays\Search::betweenBothWithKeyToValue',
            'searchvaluetokeybetweenboth'  => 'Arrays\Search::betweenBothWithValueToKey'
        ]
    ];

    /**
     * Functionalization
     * 
     * @var array
     */
    const functionalization = 
    [
        'merge'             => 'array_merge',
        'recursivemerge'    => 'array_merge_recursive',
        'flip'              => 'array_flip',
        'transform'         => 'array_flip',
        'unique'            => 'array_unique',
        'deleterecurrent'   => 'array_unique',
        'range'             => 'range',
        'series'            => 'range',
        'slice'             => 'array_slice',
        'section'           => 'array_slice',
        'splice'            => 'array_splice',
        'resection'         => 'array_splice',
        'rand'              => 'array_rand',
        'random'            => 'array_rand',
        'map'               => 'array_map',
        'implementcallback' => 'array_map',
        'count'             => 'count',
        'length'            => 'count',
        'column'            => 'array_column',
        'product'           => 'array_product',
        'sum'               => 'array_sum',
        'intersect'         => 'array_intersect',
        'intersectkey'      => 'array_intersect_key',
        'chunk'             => 'array_chunk',
        'apportion'         => 'array_chunk',
        'key'               => 'key',
        'current'           => 'current',
        'value'             => 'current',
        'values'            => 'array_values',
        'keys'              => 'array_keys'
    ];

    /**
     * Combine
     * 
     * @param array $keys
     * @param array $values = []
     * 
     * @return array
     */
    public static function combine(array $keys, array $values = []) : array
    {
        if( empty($values) )
        {
            $values = $keys;
        }

        return array_combine($keys, $values);
    }

    /**
     * Multiple Key
     * 
     * @param array  $array
     * @param string $keySplit = '|'
     * 
     * @return array
     */
    public static function multikey(array $array, string $keySplit = '|') : array
    {
        return Datatype::multikey($array, $keySplit);
    }

    /**
     * Value Exists
     * 
     * @param array $array
     * @param mixed $element
     * @param bool  $strict = false
     * 
     * @return bool
     */
    public static function valueExists(array $array, $element, bool $strict = false) : bool
    {
        return in_array($element, $array, $strict);
    }

    /**
     * Value Exists Insensitive
     * 
     * @param array $array
     * @param mixed $element
     * @param bool  $strict = false
     * 
     * @return bool
     */
    public static function valueExistsInsensitive(array $array, $element, bool $strict = false) : bool
    {
        return self::valueExists(array_map('strtolower', $array), strtolower($element ?? ''), $strict);
    }

    /**
     * Key Exists
     * 
     * @param array $array
     * @param mixed $key
     * 
     * @return bool
     */
    public static function keyExists(array $array, $key) : bool
    {
        return isset($array[$key]);
    }

    /**
     * Key Exists Insensitive
     * 
     * @param array $array
     * @param mixed $key
     * 
     * @return bool
     */
    public static function keyEsistsInsensitive(array $array, $key) : bool
    {
        return self::keyExists(array_change_key_case($array), strtolower($key ?? ''));
    }

    /**
     * Search
     * 
     * @param array $array
     * @param mixed $element
     * @param bool  $strict = false
     * 
     * @return bool
     */
    public static function search(array $array, $element, bool $strict = false)
    {
        return array_search($element, $array, $strict);
    }

    /**
     * Count Same Values
     * 
     * @param array $array
     * @param mixed $key = NULL
     * 
     * @return int|false
     */
    public static function countSameValues(array $array, string $key = NULL)
    {
        $return = array_count_values($array);

        if( ! empty($key) )
        {
            return $return[$key] ?? false;
        }

        return $return;
    }
}
