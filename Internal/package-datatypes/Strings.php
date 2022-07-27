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

use ZN\Helper;
use ZN\Controller\Factory;
use ZN\Ability\Functionalization;

class Strings extends Factory
{
    use Functionalization;

    const factory =
    [
        'methods' =>
        [
            'mtrim'            => 'Strings\Trim::middle',
            'trimslashes'      => 'Strings\Trim::slashes',
            'addprefix'        => 'Strings\Trim::prefix',
            'removeprefix'     => 'Strings\Trim::removePrefix',
            'addsuffix'        => 'Strings\Trim::suffix',
            'removesuffix'     => 'Strings\Trim::removeSuffix',
            'addbothfix'       => 'Strings\Trim::presuffix',
            'removebothfix'    => 'Strings\Trim::removePresuffix',
            'casing'           => 'Strings\Casing::use',
            'lowercase'        => 'Strings\Casing::lower',
            'uppercase'        => 'Strings\Casing::upper',
            'titlecase'        => 'Strings\Casing::title',
            'pascalcase'       => 'Strings\Casing::pascal',
            'camelcase'        => 'Strings\Casing::camel',
            'underscorecase'   => 'Strings\Casing::underscore',
            'search'           => 'Strings\Search::use',
            'searchposition'   => 'Strings\Search::position',
            'searchstring'     => 'Strings\Search::string',
            'searchbetween'    => 'Strings\Search::between',
            'searchbetweenboth'=> 'Strings\Search::betweenBoth',
            'reshuffle'        => 'Strings\Substitution::reshuffle',
            'placement'        => 'Strings\Substitution::placement',
            'replace'          => 'Strings\Substitution::replace',
            'repeatcomplete'   => 'Strings\Substitution::repeatComplete',
            'addslashes'       => 'Strings\Security::addSlashes',
            'removeslashes'    => 'Strings\Security::removeSlashes',
            'section'          => 'Strings\Section::use',
            'splituppercase'   => 'Strings\Split::upperCase',
            'apportion'        => 'Strings\Split::apportion',
            'divide'           => 'Strings\Split::divide',
            'removeelement'    => 'Strings\Element::remove',
            'removefirst'      => 'Strings\Element::removeFirst',
            'removelast'       => 'Strings\Element::removeLast',
        ]
    ];

    /**
     * Functionalization
     * 
     * @var array
     */
    const functionalization = 
    [
        'repeat' => 'str_repeat',
        'length' => 'mb_strlen',
        'split'  => 'str_split'
    ];

    /**
     * String to Array
     * 
     * @param string $string
     * @param string $split = ' '
     * 
     * @return array
     */
    public static function toArray(string $string, string $split = ' ')
    {
        if( empty($split) )
        {
            return str_split($string, 1); // @codeCoverageIgnore
        }

        return explode($split, $string);
    }

    /**
     * Pad
     * 
     * @param string $string
     * @param int    $count = 1
     * @param string $chars = ' '
     * @param string $type  = 'right' - options[right|left|both]
     * 
     * @return string
     */
    public static function pad(string $string, int $count = 1, string $chars = ' ', string $type = 'right') : string
    {
        return str_pad($string, $count, $chars, Helper::toConstant($type, 'STR_PAD_'));
    }

    /**
     * Recurrent Count
     * 
     * @param string $str
     * @param string $char
     * 
     * @return int
     */
    public static function recurrentCount(string $str, string $char) : int
    {
        return count(explode($char, $str)) - 1;
    }
}
