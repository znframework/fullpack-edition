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

trait BootstrapAttributes
{
    /**
     * Bootstrap opover attribute
     * 
     * @param string $placement
     * @param string $content
     * 
     * @return this
     */
    public function popover(String $placement, $content = NULL)
    {
        if( is_string($content) )
        {
            return $this->dataContainer('body')->dataToggle('popover')->dataPlacement($placement)->dataContent($content);
        }

        return $this->usePropertyOptions($placement, $content, __FUNCTION__);
    }

    /**
     * Bootstrap opover attribute
     * 
     * @param string $placement
     * @param string $content
     * @param bool   $html = NULL
     * 
     * @return this
     */
    public function tooltip(String $placement, $content = NULL, Bool $html = NULL)
    {
        if( is_string($content) )
        {
            return $this->title($content)->dataHtml($html === true ? 'true' : $html)->dataToggle('tooltip')->dataPlacement($placement);
        }
        
        return $this->usePropertyOptions($placement, $content, __FUNCTION__);
    }

    /**
     * Protected use property options
     */
    protected function usePropertyOptions($selector, $content, $type)
    {
        $this->isBootstrapAttribute('on', function($return) use($type)
        {
            $this->settings['attr']['on'] = Base::suffix($return, '.bs.' . $type);
        });

        return $this->bootstrapObjectOptions($selector === 'all' ? '[data-toggle="'.$type.'"]' : $selector, $content ?? [], $type);
    }
}
