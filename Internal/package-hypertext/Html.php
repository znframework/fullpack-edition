<?php namespace ZN\Hypertext;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\IS;
use ZN\Base;
use ZN\Request;
use ZN\Buffering;
use ZN\Hypertext\Exception\InvalidArgumentException;

class Html
{
    use ViewCommonTrait;

    protected $elements =
    [
        'multiElement' =>
        [
            'html'  , 'body', 'head'  , 'title' , 'pre' ,
            'iframe', 'li'  , 'strong', 'span',

            'bold'      => 'b'  , 'italic'   => 'em' , 'parag'     => 'p',
            'overline'  => 'del', 'overtext' => 'sup', 'underline' => 'u',
            'undertext' => 'sub'
        ],

        'singleElement' =>
        [
            'hr', 'keygen'
        ],

        'mediaContent' =>
        [
            'audio', 'video'
        ],

        'media' =>
        [
            'embed', 'source'
        ],

        'contentAttribute' =>
        [
            'div'   , 'canvas'    , 'command' , 'datalist', 'details',
            'dialog', 'figcaption', 'figure'  , 'mark'    , 'meter'  ,
            'time'  , 'summary'   , 'progress', 'output'  ,
        ],

        'content' =>
        [
            'aside'  , 'article', 'footer',  'header', 'nav',
            'section', 'hgroup'
        ]
    ];

    /**
     * Sets ul attributes [5.0.0]
     * 
     * @param callable $list
     * @param array    $attributes = []
     * 
     * @return string
     */
    public function ul(callable $list, array $attributes = [], $type = 'ul') : string
    {
        return $this->_multiElement($type, Buffering\Callback::do($list, [new $this]), $attributes);
    }

    /**
     * Sets ol attributes [5.0.0]
     * 
     * @param callable $list
     * @param array    $attributes = []
     * 
     * @return string
     */
    public function ol(callable $list, array $attributes = []) : string
    {
        return $this->ul($list, $attributes, 'ol');
    }

    /**
     * Creates form input
     * 
     * @return Form
     */
    public function form() : Form
    {
        return new Form;
    }

    /**
     * Creates table
     * 
     * @return Table
     */
    public function table() : Table
    {
        return new Table;
    }

    /**
     * Creates list
     * 
     * @return Lists
     */
    public function list() : Lists
    {
        return new Lists;
    }

    /**
     * Creates image element
     * 
     * @param string $src
     * @param int    $width
     * @param int    $height = NULL
     * @param array  $attributes = []
     * 
     * @return string
     */
    public function image(string $src, int $width = NULL, int $height = NULL, array $attributes = []) : string
    {
        if( ! IS::url($src) )
        {
            $src = Request::getBaseURL($src);
        }

        $attributes['src'] = $src;

        if( ! empty($width) )
        {
            $attributes['width'] = $width;
        }

        if( ! empty($height) )
        {
            $attributes['height'] = $height;
        }

        if( ! isset($attributes['title']) )
        {
            $attributes['title'] = '';
        }

        if( ! isset($attributes['alt']) )
        {
            $attributes['alt'] = '';
        }

        return $this->_singleElement('img', $attributes);
    }

    /**
     * Creates label element
     * 
     * @param string $for
     * @param mixed  $value      = NULL
     * @param string $form       = NULL
     * @param array  $attributes = []
     * 
     * @return string
     */
    public function label(string $for, $value = NULL, string $form = NULL, array $attributes = []) : string
    {
        if( ! empty($for) )
        {
            $attributes['for'] = $for;
        }

        if( ! empty($form) )
        {
            $attributes['form'] = $form;
        }

        return $this->_multiElement(__FUNCTION__, $value, $attributes);
    }

    /**
     * Creates anchor
     * 
     * @param string $url
     * @param mixed  $value      = NULL
     * @param array  $attributes = []
     * 
     * @return string
     */
    public function anchor(string $url, $value = NULL, array $attributes = []) : string
    {
        if( $url === ':void' )
        {
            $url = 'javascript:void(0);';
        }
        elseif( ! IS::url($url) && strpos($url, '#') !== 0 )
        {
            $url = Request::getSiteURL($url);
        }

        $attributes['href'] = $url;

        return $this->_multiElement('a', $value ?? $url, $attributes);
    }

    /**
     * Creates button
     * 
     * @param mixed  $value      = NULL
     * @param array  $attributes = []
     * 
     * @return string
     */
    public function button($value = NULL, array $attributes = []) : string
    {
        $this->settings['attr']['type'] = $this->settings['attr']['type'] ?? 'button';

        return $this->_multiElement('button', $value ?? $url, $attributes);
    }

    /**
     * Creates mail to element
     * 
     * @param string $mail
     * @param string $value      = NULL
     * @param array  $attributes = []
     * 
     * @return string
     */
    public function mailTo(string $mail, string $value = NULL, array $attributes = []) : string
    {
        if( ! IS::email($mail) )
        {
            throw new InvalidArgumentException('Error', 'emailParameter', '1.($mail)');
        }

        $attributes['href'] = 'mailto:' . $mail;

        return $this->_multiElement('a', $value ?? $mail, $attributes);
    }

    /**
     * Creates font elements
     * 
     * @param mixed  $str
     * @param string $size       = NULL
     * @param string $color      = NULL
     * @param string $face       = NULL
     * @param array  $attributes = []
     * 
     * @return string
     */
    public function font($str, string $size = NULL, string $color = NULL, string $face = NULL, array $attributes = []) : string
    {
        if( ! empty($size) )
        {
            $attributes['size'] = $size;
        }

        if( ! empty($color) )
        {
            $attributes['color'] = $color;
        }

        if( ! empty($face) )
        {
            $attributes['face'] = $face;
        }

        return $this->_multiElement('font', $str, $attributes);
    }

    /**
     * Creates br element
     * 
     * @param int $count = 1
     * 
     * @return string
     */
    public function br(int $count = 1) : string
    {
        return str_repeat($this->_singleElement(__FUNCTION__), $count);
    }

    /**
     * Creates script element
     * 
     * @param string $path
     * 
     * @return string
     */
    public function script(string $path) : string
    {
        if( ! IS::url($path) )
        {
            $path = Request::getBaseURL(Base::suffix($path, '.js'));
        }

        $attributes['href'] = $path;
        $attributes['type'] = 'text/javascript';

        return $this->_singleElement(__FUNCTION__, $attributes);
    }

    /**
     * Creates link
     * 
     * @param string $path
     * 
     * @return string
     */
    public function link(string $path) : string
    {
        if( ! IS::url($path) )
        {
            $path = Request::getBaseURL(Base::suffix($path, '.css'));
        }

        $attributes['href'] = $path;
        $attributes['rel']  = 'stylesheet';
        $attributes['type'] = 'text/css';

        return $this->_singleElement('link', $attributes);
    }

    /**
     * Creates space
     * 
     * @param int $count = 4
     * 
     * @return string
     */
    public function space(int $count = 4) : string
    {
        return str_repeat("&nbsp;", $count);
    }

    /**
     * Gets head element
     * 
     * @param mixed  $str
     * @param int    $type       = 3
     * @param array  $attributes = []
     * 
     * @return string
     */
    public function heading($str, int $type = 3, array $attributes = []) : string
    {
        return $this->_multiElement('h'.$type, $str, $attributes);
    }

    /**
     * Gets multiple element
     * 
     * @param string $element
     * @param mixed  $str        = NULL
     * @param array  $attributes = []
     * 
     * @return string
     */
    public function element(string $element, $str = NULL, array $attributes = []) : string
    {
        return $this->_multiElement($element, $str, $attributes);
    }

    /**
     * Gets multiple attributes
     * 
     * @param mixed $str
     * @param array $array = []
     * 
     * @return string
     */
    public function multiAttr($str, array $array = []) : string
    {
        $perm  = $this->settings['attr']['perm'] ?? NULL;
    
        $open  = '';
        $close = '';
        $att   = '';
        

        foreach( $array as $k => $v )
        {
            if( ! is_numeric($k) )
            {
                $element = $k;

                if( ! is_array($v) )
                {
                    $att = ' '.$v;
                }
                else
                {
                    $att = $this->attributes($v);
                }
            }
            else
            {
                $element = $v;
            }

            $open .= '<'.$element.$att.'>';
            $close = '</'.$element.'>'.$close;
        }
       
        return $this->_perm($perm, $open.$str.$close);
    }

    /**
     * Gets meta tag
     * 
     * @param mixed  $name
     * @param string $content = NULL
     * 
     * @return string
     */
    public function meta($name, string $content = NULL)
    {
        if( ! is_array($name) )
        {
            $this->outputElement .= $this->_singleMeta($name, $content);
        }
        else
        {
            $metas = '';

            foreach( $name as $key => $val )
            {
                $metas .= $this->_singleMeta($key, $val);
            }

            $this->outputElement .= $metas;
        }

        return $this;
    }

    /**
     * Protected Content
     */
    protected function _content($html, $type)
    {
        $type = strtolower($type ?? '');

        $perm = $this->settings['attr']['perm'] ?? NULL;

        $this->outputElement .= $this->_perm($perm, "<$type>" . $this->stringOrCallback($html) . "</$type>");

        return $this;
    }

    /**
     * Protected Content Attribute
     */
    protected function _contentAttribute($content, $_attributes, $type)
    {
        $type   = strtolower($type ?? '');

        $perm   = $this->settings['attr']['perm'] ?? NULL;
        
        $return = '<'.$type.$this->attributes($_attributes).'>'.$this->stringOrCallback($content)."</$type>".EOL;

        $this->outputElement .= $this->_perm($perm, $return);

        return $this;
    }

    /**
     * Protected Media
     */
    protected function _media($src, $_attributes, $type)
    {
        $perm = $this->settings['attr']['perm'] ?? NULL;

        $this->outputElement .= $this->_perm($perm, '<'.strtolower($type).' src="'.$src.'"'.$this->attributes($_attributes).'>'.EOL);

        return $this;
    }

    /**
     * Protected Media Content
     */
    protected function _mediaContent($src, $content, $_attributes, $type)
    {
        $type = strtolower($type ?? '');

        $perm = $this->settings['attr']['perm'] ?? NULL;

        $this->outputElement .= $this->_perm($perm, '<'.$type.' src="'.$src.'"'.$this->attributes($_attributes).'>'.$this->stringOrCallback($content)."</$type>".EOL);
    
        return $this;
    }

    /**
     * Protected Element
     */
    protected function _multiElement($element, $str, $attributes = [])
    {
        $element = strtolower($element ?? '');

        $perm = $this->settings['attr']['perm'] ?? NULL;

        $this->outputElement .= $this->_perm($perm, '<'.$element.$this->attributes($attributes).'>'.$this->stringOrCallback($str).'</'.$element.'>');

        return $this;
    }

    /**
     * Protected Single Element
     */
    protected function _singleElement($element, $attributes = [])
    {
        $perm = $this->settings['attr']['perm'] ?? NULL;

        $this->outputElement .= $this->_perm($perm, '<'.strtolower($element ?? '').$this->attributes($attributes).'>');

        return $this;
    }

    /**
     * Protected Single Meta
     */
    protected function _singleMeta($name, $content)
    {
        if( stripos($name, 'http:') === 0 )
        {
            $name = ' http-equiv="'.str_ireplace('http:', '', $name).'"';
        }
        elseif( stripos($name, 'property:') === 0 )
        {
            $name = ' property="'.str_ireplace('property:', '', $name).'"';
        }
        else
        {
            $name = ' name="'.str_ireplace('name:', '', $name).'"';
        }

        if( ! empty($content) )
        {
            $content = ' content="'.$content.'"';
        }
        else
        {
            $content = '';
        }

        return '<meta' . $name . $content . ' />' . EOL;
    }
}
