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

        $this->isBootstrapAttribute('dismiss-button', function() use(&$content)
        {
            $content .= $this->buttonDismissButton($this->spanDismissButton());  
        });

        return $this->role('alert')->class('alert alert-' . $type)->div($content);
    }

    /**
     * Bootstrap breadcrumb
     * 
     * @param string $uri = NULL
     */
    public function breadcrumb(String $uri = NULL, Int $segmentCount = -1 ) 
    {
        $uris = $this->getURIsegments($uri, $segmentCount);
        $list = $this->breadcrumbOlList($uris);

        return $this->breadcrumbNav($list);
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
            unset($this->settings['attr'][$attr]);

            $callback();
        }
    }

    /**
     * Protected close button
     */
    protected function spanDismissButton()
    {
        return $this->ariaHidden('true')->span('&times;');
    }

    /**
     * Protected button dismiss button
     */
    protected function buttonDismissButton($content)
    {
        return $this->class('close')->dataDismiss('alert')->ariaLabel('Close')->button($content);
    }
}