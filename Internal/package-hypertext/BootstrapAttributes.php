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

    /**
     * Protected use property scripts
     */
    protected function usePropertyScripts($selector, $content, $type)
    {
        $this->isBootstrapAttribute('on', function($return) use($type)
        {
            $this->settings['attr']['on'] = Base::suffix($return, '.bs.' . $type);
        });

        return $this->bootstrapObjectOptions((ctype_alpha($selector[0]) ? '#' : '') . $selector, $this->stringOrCallback($content), $type);
    }
}
