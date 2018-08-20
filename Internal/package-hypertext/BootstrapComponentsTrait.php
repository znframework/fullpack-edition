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

use ZN\Base;
use ZN\Config;
use ZN\Datatype;
use ZN\Request\URI;

trait BootstrapComponentsTrait
{
    /**
     * Bootstrap carousel
     * 
     * @param string ...$images
     * 
     * @return string
     */
    public function carousel(String $id = NULL, Array $images = [])
    {
        $images = $this->transferAttributesAndUnset('attr', 'item') ?: $images;

        $data = 
        [
            'carouselId'        => $id ?? ('Carousel' . md5(uniqid())),
            'carouselImages'    => $images,
            'carouseIndicators' => $this->transferAttributesAndUnset('attr', 'indicators'),
            'carouselPrevName'  => $this->transferAttributesAndUnset('attr', 'prev') ?: 'Previous',
            'carouselNextName'  => $this->transferAttributesAndUnset('attr', 'next') ?: 'Next' 
        ];

        foreach( ['interval', 'keyboard', 'ride', 'pause', 'wrap'] as $opt )
        {
            $this->addBootstrapOption($opt, $this->transferAttributesAndUnset('attr', $opt), $options);
        }

        $transition = $this->transferAttributesAndUnset('attr', 'transition');

        $this->isBootstrapAttribute('on', function($return)
        {
            $this->settings['attr']['on'] = Base::suffix($return, '.bs.carousel');
        });

        $this->bootstrapObjectOptions($id, $transition ?? $options, 'carousel');

        return $this->getCarouselResource('standart', $data);
    }   

    /**
     * Active caroseul options
     */
    public function activeCarouselOptions(String $id)
    {
        return $this->bootstrapOptions[$id] ?? NULL;
    }

    /**
     * P
     */
    public function item(String $file, Array $attributes = [])
    {
        if( empty($attributes) )
        {
            $this->settings['attr']['item'][] = $file;
        }
        else
        {
            $this->settings['attr']['item'][$file] = $attributes;
        }  

        return $this;
    }

    /**
     * Bootstrap alert component
     * 
     * @param string $type
     * @param string|callable $content
     * 
     * @return string
     * 
     */
    public function alert(String $type, $content)
    {
        $content = $this->stringOrCallback($content);

        $this->isBootstrapAttribute('dismiss-fade', function() use(&$type)
        {
            $type .= ' alert-dismissible fade show';
        });

        $this->isBootstrapAttribute('dismiss-button', function($attribute) use(&$content)
        {
            $content .= $this->buttonDismissButton($this->spanDismissButton($attribute));  
        });

        return $this->role('alert')->class('alert alert-' . $type)->div($content);
    }

    /**
     * Bootstrap breadcrumb
     * 
     * @param string $uri = NULL
     */
    public function breadcrumb(String $uri = NULL, Int $segmentCount = -1) 
    {
        $uris = $this->getURIsegments($uri, $segmentCount);
        $list = $this->breadcrumbOlList($uris);

        return $this->breadcrumbNav($list);
    }

    /**
     * Protected object options
     */
    protected function bootstrapObjectOptions(String $id, $options, $type)
    {
        $return  = '<script>$("' . Base::prefix($id, '#') . '")';
        
        if( $options )
        {
            $output = true;
            $return .= '.' . $type . '(' . json_encode($options) . ')';
        }
        
        if( $parameter = $this->transferAttributesAndUnset('attr', 'on') )
        {
            $output = true;
            $return .= '.on(\'' . $parameter . '\', function(){' . $this->transferAttributesAndUnset('attr', 'onCallback') . '})';
        }

        $return .= ';</script>';

        $this->bootstrapOptions[$id] = isset($output) ? $return : NULL;
    }
    
    /**
     * Protected add bootstrap option
     */
    protected function addBootstrapOption($key, $value, &$options)
    {
        if( isset($value) )
        {
            $options[$key] = $value;
        }
    }

    /**
     * Protected get uri segments
     */
    protected function getURIsegments($uri, $segmentCount)
    {
        if( $uri === NULL)
        {
            $uri = URI::active();
        }

        
        return explode('/' , rtrim(Datatype::divide($uri, '/', 0, $segmentCount), '/'));
    }

    /**
     * Protected breadcrumb ol list
     */
    protected function breadcrumbOlList($options)
    {
        return $this->ol(function($option) use($options)
        {
            $link  = NULL;
            $count = count($options);

            if( $count === 2 && $options[1] === CURRENT_COPEN_PAGE )
            {
                unset($options[1]);

                $count--;
            }
            
            foreach( $options as $key => $val )
            {
                $link .= Base::suffix($val);

                if( $key < $count - 1 )
                {
                    $item = $this->breadcrumbItem($link, $val);

                    echo $option->class('breadcrumb-item active')->ariaCurrent('page')->li($item);
                }
                else
                {
                    echo $option->class('breadcrumb-item')->li(ucfirst($val));
                }
            }
        }, ['class' => 'breadcrumb']);   
    }

    /**
     * Protected breadcrumb nav
     */
    protected function breadcrumbNav($content)
    {
        return '<nav aria-label="breadcrumb">' . $content . '</nav>'; 
    }

    /**
     * Protected breadcrumb item
     */
    protected function breadcrumbItem($link, $content)
    {
        return $this->anchor($link, ucfirst($content));
    }

    /**
     * Protected is bootstrap attribute
     */
    protected function isBootstrapAttribute($attr, $callback)
    {
        if( isset($this->settings['attr'][$attr]) )
        {
            $attribute = $this->settings['attr'][$attr];

            if( $attribute === str_replace('-', '', $attr) )
            {
                $attribute = NULL;
            }

            unset($this->settings['attr'][$attr]);

            $callback($attribute);
        }
    }

    /**
     * Protected close button
     */
    protected function spanDismissButton($attribute)
    {
        return $this->ariaHidden('true')->span($attribute ?? '&times;');
    }

    /**
     * Protected button dismiss button
     */
    protected function buttonDismissButton($content)
    {
        return $this->class('close')->dataDismiss('alert')->ariaLabel('Close')->button($content);
    }
}