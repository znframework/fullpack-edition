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
use ZN\Buffering;
use ZN\Request\URI;

trait BootstrapComponents
{
    /**
     * Use of bootstrap group
     * 
     * @param string|callable $code  = ''
     * @param string          $class = ''
     * 
     * @return string|this
     */
    public function group($code = '', String $class = '')
    {
        if( is_string($code) )
        {
            $this->settings['group']['class'] = $this->bootstrapClassResolution('form-group', $code);

            return $this;
        }
        elseif( is_callable($code) )
        {
            $this->callableGroup = true;

            $result = $this->getHTMLClass()
                           ->class('form-group row' . Base::prefix($class, ' '))
                           ->div(EOL . Buffering\Callback::do($code));

            unset($this->callableGroup);
            
            return $result;
        }
    }

    /**
     * Use of bootstrap label
     * 
     * @param string $for   = NULL
     * @param string $value = NULL
     * @param string $class = NULL
     * 
     * @return this
     */
    public function label(String $for = NULL, String $value = NULL, String $class = NULL)
    {
        $this->settings['label']['for'  ] = $for;
        $this->settings['label']['value'] = $value;
        $this->settings['label']['class'] = $class;

        return $this;
    }

    /**
     * Bootstrap col size
     * 
     * @param string $size
     * 
     * @return this
     */
    public function col(String $size)
    {
        $this->settings['col']['size'] = $size;

        return $this;
    }

    /**
     * Protected getHTMLClass
     */
    protected function getHTMLClass()
    {
        return new Html;
    }
    
    /**
     * Help text
     * 
     * @param string $content
     * @param string $class = NULL
     * 
     * @return this
     */
    public function helptext(String $content, String $class = '')
    {
        $this->settings['help']['text'] = $this->getHTMLClass()
                                               ->class('help-block' . Base::prefix($class, ' '))
                                               ->span($content);

        return $this;
    }   

    /**
     * Help text
     * 
     * @param string $content
     * @param string $class = NULL
     * 
     * @return this
     */
    public function helptext4(String $content, String $class = '')
    {
        $this->settings['help']['text'] = $this->getHTMLClass()
                                               ->class('form-text text-muted' . Base::prefix($class, ' '))
                                               ->span($content);

        return $this;
    }  

    /**
     * Open modal
     * 
     * @param string $selector
     * 
     * @return this
     */
    public function modal(String $selector)
    {
        $this->settings['attr']['data-toggle'] = 'modal';
        $this->settings['attr']['data-target'] = Base::prefix($selector, '#');

        return $this;
    }

    /**
     * Generate modal box
     * 
     * @param string $id
     * @param array  $data
     * 
     * @return string
     */
    public function modalbox(String $id, Array $data = [], $template = 'standart')
    {
        $attr = $this->settings['attr'] ?? [];
        
        $this->settings['attr'] = [];

        $data = 
        [
            'modalId'            => $id,
            'modalHeader'        => $this->stringOrCallback($attr['modal-header'] ?? ''),
            'modalBody'          => $this->stringOrCallback($attr['modal-body'] ?? ''),
            'modalFooter'        => $this->stringOrCallback($attr['modal-footer'] ?? ''),
            'modalSize'          => $attr['modal-size'] ?? '',
            'modalDismissButton' => $attr['modal-dismiss-button'] ?? ''
        ];

        return $this->getModalResource($template, $data);
    }
    
    /**
     * Generate modal box bootstrap 4
     * 
     * @param string $id
     * @param array  $data
     * 
     * @return string
     */
    public function modalbox4(String $id, Array $data = [])
    {
        return $this->modalbox($id, $data, 'standart4');
    }

    /**
     * Generate toast bootstrap 4
     * 
     * @param string $id
     * @param array  $data
     * 
     * @return string
     */
    public function toast(String $id, Array $data = [], $template = 'standart')
    {
        $attr = $this->settings['attr'] ?? [];
        
        $this->settings['attr'] = [];

        $data = 
        [
            'toastId'            => $id,
            'toastHeader'        => $this->stringOrCallback($attr['toast-header'] ?? ''),
            'toastBody'          => $this->stringOrCallback($attr['toast-body'] ?? ''),
            'toastDismissButton' => $attr['toast-dismiss-button'] ?? '',
            'toastAutoHide'      => $attr['toast-auto-hide'] ?? 'true'
        ];

        return $this->getToastResource($template, $data);
    }

    /**
     * Toast event
     * 
     * @param string $selector
     * @param string|callback $content = ''
     * 
     * @return string
     */
    public function toastEvent(String $selector, $content = '')
    {
        return $this->usePropertyScripts($selector, $content, 'toast');
    }

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
     * Popover event
     * 
     * @param string $selector
     * @param string|callback $content = ''
     * 
     * @return string
     */
    public function popoverEvent(String $selector, $content = NULL)
    {
        return $this->usePropertyOptions($selector, $content, 'popover');
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
     * Tooltip event
     * 
     * @param string $selector
     * @param string|callback $content = ''
     * 
     * @return string
     */
    public function tooltipEvent(String $selector, $content = NULL)
    {
        return $this->usePropertyOptions($selector, $content, 'tooltip');
    }
    
    /**
     * Bootstrap carousel
     * 
     * @param string ...$images
     * 
     * @return string
     */
    public function carousel(String $id = NULL, Array $images = [], $view = 'standart')
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

        $this->bootstrapObjectOptions(Base::prefix($id, '#'), $transition ?? $options, 'carousel');

        return $this->getCarouselResource($view, $data);
    }   
    
    /**
     * Bootstrap carousel 4
     * 
     * @param string ...$images
     * 
     * @return string
     */
    public function carousel4(String $id = NULL, Array $images = [])
    {
        return $this->carousel($id, $images, 'standart4');
    }

    /**
     * Active caroseul options
     */
    public function activeCarouselOptions(String $id)
    {
        return $this->bootstrapOptions['carousel'][Base::prefix($id, '#')] ?? NULL;
    }

    /**
     * Item 
     * 
     * @param string $file
     * @param array  $attributes = []
     * 
     * @return self
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
     * @param string|callback $content
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
            $content .= $this->buttonDismissButton((string) $this->spanDismissButton($attribute));  
        });

        return $this->role('alert')->class('alert alert-' . $type)->div($content);
    }

    /**
     * Bootstrap 4 badge component
     * 
     * @param string $type
     * @param string|callback $content
     * 
     * @return string
     * 
     */
    public function badge4(String $type, $content)
    {
        $content = $this->stringOrCallback($content);

        return $this->class('badge badge-' . $type)->span($content);
    }

    /**
     * Bootstrap badge component
     * 
     * @param string $type
     * @param string|callback $content
     * 
     * @return string
     * 
     */
    public function badge(String $type, $content)
    {
        $content = $this->stringOrCallback($content);

        return $this->class('label label-' . $type)->span($content);
    }

    /**
     * Bootstrap progress bar animated attribute
     * 
     * @return self
     */
    public function progressbarAnimated()
    {
        $this->settings['progressbarAnimated'] = ' progress-bar-striped progress-bar-animated';

        return $this;
    }

    /**
     * Bootstrap progress bar striped attribute
     * 
     * @return self
     */
    public function progressbarStriped()
    {
        $this->settings['progressbarStriped'] = ' progress-bar-striped';

        return $this;
    }

    /**
     * Bootstrap progress bar text attribute
     * 
     * @return self
     */
    public function progressbarText(String $text)
    {
        $this->settings['progressbarText'] = ' ' . $text;

        return $this;
    }

    /**
     * Bootstrap 4 progress bar component
     * 
     * @param string $type
     * @param float  $percent
     * @param float  $height = NULL
     * 
     * @return string
     * 
     */
    public function progressbar4(String $type, Float $percent, Float $height = NULL)
    {
        $attr = NULL;

        if( isset($this->settings['progressbarAnimated']) )
        {
            $attr = $this->settings['progressbarAnimated']; unset($this->settings['progressbarAnimated']);     
        }

        if( isset($this->settings['progressbarStriped']) )
        {
            $attr = $this->settings['progressbarStriped']; unset($this->settings['progressbarStriped']);     
        }

        if( isset($this->settings['progressbarText']) )
        {
            $text = $this->settings['progressbarText']; unset($this->settings['progressbarText']);     
        }


        $content = (string) $this->class('progress-bar bg-' . $type . $attr)->style('width:' . $percent . '%')->div('%' . $percent . ($text ?? ''));

        if( $height )
        {
            $this->style('height:' . $height . 'px');
        }

        return $this->class('progress')->div($content);
    }

    /**
     * Bootstrap 4 progress bar component
     * 
     * @param string $type
     * @param float  $percent
     * @param float  $height = NULL
     * 
     * @return string
     * 
     */
    public function progressbar(String $type, Float $percent, Float $height = NULL)
    {
        $attr = NULL;

        if( isset($this->settings['progressbarAnimated']) )
        {
            $attr = $this->settings['progressbarAnimated']; unset($this->settings['progressbarAnimated']);     
        }

        if( isset($this->settings['progressbarStriped']) )
        {
            $attr = $this->settings['progressbarStriped']; unset($this->settings['progressbarStriped']);     
        }

        if( isset($this->settings['progressbarText']) )
        {
            $text = $this->settings['progressbarText']; unset($this->settings['progressbarText']);     
        }

        $content = (string) $this->class('progress-bar progress-bar-' . $type . $attr)->style('width:' . $percent . '%')->div('%' . $percent . ($text ?? ''));

        if( $height )
        {
            $this->style('height:' . $height . 'px');
        }

        return $this->class('progress')->div($content);
    }

    /**
     * Generate bootstrap filter
     * 
     * @param string $source
     * @param string $target
     * @param string $event = NULL
     * 
     * @return string
     */
    public function filterEvent(String $source, String $target, String $event = NULL, $template = 'standart')
    {
        $data = 
        [
            'filterSource' => $source,
            'filterTarget' => $target,
            'filterEvent'  => $event
        ];

        return $this->getResource($template, $data, 'Filters');
    }

    /**
     * Generate bootstrap media object reply
     * 
     * @param string|callback $content
     * 
     * @return self
     */
    public function mediaObjectReply($content)
    {
        $this->settings['mediaObjectReply'] = $this->stringOrCallback($content);

        return $this;
    }

    /**
     * Generate bootstrap 4 media object
     * 
     * @param string $avatar
     * @param string $name
     * @param string $content
     * @param string $date = NULL
     * 
     * @return string
     */
    public function mediaObject4(String $avatar, String $name, String $content, String $date, $template = 'standart')
    { 
        $attr = $this->settings['attr'] ?? [];

        $this->settings['attr'] = [];

        $data = 
        [
            'mediaObjectAvatar'         => $avatar,
            'mediaObjectName'           => $name,
            'mediaObjectContent'        => $content,
            'mediaObjectDate'           => $date,
            'mediaObjectPadding'        => $attr['media-object-padding']        ?? NULL,
            'mediaObjectAvatarMargin'   => $attr['media-object-avatar-margin']  ?? NULL,
            'mediaObjectAvatarSize'     => $attr['media-object-avatar-size']    ?? NULL,
            'mediObjectAvatarType'      => $attr['media-object-avatar-type']    ?? NULL,
            'mediaObjectReply'          => $this->settings['mediaObjectReply'] ?? NULL
        ];

        unset($this->settings['mediaObjectAnswer']);

        return $this->getResource($template, $data, 'MediaObjects');
    }

    /**
     * Fontawesome Icon
     * 
     * @param string $icon
     * @param string $size = NULL
     * @param string $type = '
     */
    public function faIcon(String $icon, String $size = NULL, $type = '')
    {
        return '<i class="fa' . $type . ' fa-' . $icon . ($size ? ' fa-' . $size : NULL) . '"></i>';
    }

    /**
     * Fontawesome Icon
     * 
     * @param string $icon
     * @param string $size = NULL
     * @param string $type = '
     */
    public function falIcon(String $icon, String $size = NULL)
    {
        return $this->faIcon($icon, $size, 'l');
    }

    /**
     * Fontawesome Icon
     * 
     * @param string $icon
     * @param string $size = NULL
     * @param string $type = '
     */
    public function fasIcon(String $icon, String $size = NULL)
    {
        return $this->faIcon($icon, $size, 's');
    }

    /**
     * Fontawesome Icon
     * 
     * @param string $icon
     * @param string $size = NULL
     * @param string $type = '
     */
    public function fadIcon(String $icon, String $size = NULL)
    {
        return $this->faIcon($icon, $size, 'd');
    }

    /**
     * Fontawesome Icon
     * 
     * @param string $icon
     * @param string $size = NULL
     * @param string $type = '
     */
    public function farIcon(String $icon, String $size = NULL)
    {
        return $this->faIcon($icon, $size, 'r');
    }

    /**
     * Bootstrap flex
     * 
     * @param string|callback $content
     * @param string          $class = NULL
     * 
     * @return string
     * 
     */
    public function flex($content, String $class = NULL)
    {
        $attr =  $this->settings['attr'] ?? [];

        $this->settings['attr'] = [];

        $content = $this->stringOrCallback($content);

        $size = isset($attr['flex-size']) ? $attr['flex-size'] . '-' : NULL;

        $type = (isset($attr['flex-inline']) ? 'd-' . $size . 'inline-flex' : 'd-' . $size . 'flex') . ' ';

        if( isset($attr['flex-wrap']) )
        {
            $param = $this->getFlexParameters($attr['flex-wrap'], 'object');

            switch($param->param)
            {
                case 'reverse'   : $class .= ' flex-' . $param->size . 'wrap-reverse'; break;
                case 'no'        : $class .= ' flex-' . $param->size . 'nowrap'      ; break;

                default          : $class .= ' flex-' . ($param->param ? $param->param . '-' : NULL) . 'wrap';    
            }
        }

        if( isset($attr['flex-direction']) )    $class .= ' flex-'            . $this->getFlexParameters($attr['flex-direction']);    
        if( isset($attr['flex-justify']) )      $class .= ' justify-content-' . $this->getFlexParameters($attr['flex-justify']);    
        if( isset($attr['flex-align']) )        $class .= ' align-content-'   . $this->getFlexParameters($attr['flex-align']);
        if( isset($attr['flex-align-items']) )  $class .= ' align-items-'     . $this->getFlexParameters($attr['flex-align-items']);

        return $this->class($type . $class)->div($content);
    }

    /**
     * Bootstrap flex item
     * 
     * @param string|callback $content
     * @param string          $class = NULL
     * 
     * @return string
     * 
     */
    public function flexItem($content, String $class = NULL)
    {
        $attr =  $this->settings['attr'] ?? [];

        $this->settings['attr'] = [];

        $content = $this->stringOrCallback($content);
   
        if( isset($attr['flex-fill']) )         $class .= ' flex-'        . ($attr['flex-fill'] === 'flexfill' ? NULL : $attr['flex-fill'] . '-') . 'fill';
        if( isset($attr['flex-grow']) )         $class .= ' flex-'        . $this->getFlexParameters($attr['flex-grow'], 'grow-');
        if( isset($attr['flex-shrink']) )       $class .= ' flex-'        . $this->getFlexParameters($attr['flex-shrink'], 'shrink-');
        if( isset($attr['flex-order']) )        $class .= ' order-'       . $this->getFlexParameters($attr['flex-order']);
        if( isset($attr['flex-align-self']) )   $class .= ' align-self-'  . $this->getFlexParameters($attr['flex-align-self']);

        switch($attr['flex-push'] ?? NULL)
        {
            case 'right' : $class .= ' ml-auto'; break;
            case 'left'  : $class .= ' mr-auto'; break;
        }

        return $this->class($class)->div($content);
    }

    /**
     * Bootstrap spinner component
     * 
     * @param string $type
     * @param string $color
     * @param string $size
     * 
     * @return string
     * 
     */
    public function spinner(String $type = 'border', String $color = NULL, String $size = NULL)
    {
        return $this->class('spinner-' . $type . ($color ? ' text-' . $color : NULL) . ($size ? ' spinner-' . $type . '-' . $size : NULL) )->div();
    }

    /**
     * Bootstrap spinner border component
     * 
     * @param string $color
     * @param string $size
     * 
     * @return string
     * 
     */
    public function spinnerBorder(String $color = NULL, String $size = NULL)
    {
        return $this->spinner('border', $color, $size);
    }

    /**
     * Bootstrap spinner grow component
     * 
     * @param string $color
     * @param string $size
     * 
     * @return string
     * 
     */
    public function spinnerGrow(String $color = NULL, String $size = NULL)
    {
        return $this->spinner('grow', $color, $size);
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
     * Protected get flex parameters
     */
    protected function getFlexParameters($param, $fix = NULL)
    {
        $paramEx = explode(',', $param);
        $param = $paramEx[0];
        $size  =  trim($paramEx[1] ?? '');
        $size  = ($size ? $size . '-' : '');
        
        if( $fix === 'object' )
        {
            return (object)['size' => $size, 'param' => $param];
        }

        return $size . $fix . $param;
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