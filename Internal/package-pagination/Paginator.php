<?php namespace ZN\Pagination;
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
use ZN\Request\URL;
use ZN\Request\URI;

class Paginator implements PaginatorInterface
{
    /**
     * Keep settings
     * 
     * @var array
     */
    protected $settings = [];

    /**
     * Keeps class attibute
     * 
     * @var string
     */
    protected $classAttribute; 
    
    /**
     * Keeps style attibute
     * 
     * @var string
     */
    protected $styleAttribute;

    /**
     * Default total rows
     * 
     * @var int
     */
    protected $totalRows = 50;
    
    /**
     * Default start value
     * 
     * @var int
     */
    protected $start = 0;

    /**
     * Default pagination type
     * 
     * @var string
     */
    protected $type = 'classic';

    /**
     * Default limit value
     * 
     * @var int
     */
    protected $limit = 10;

    /**
     * Default count links
     * 
     * @var int
     */
    protected $countLinks = 10;

    /**
     * Keep class value
     * 
     * @var array
     */
    protected $class = [];

    /**
     * Keep style value
     * 
     * @var array
     */
    protected $style = [];

    /**
     * Default prev tag name
     * 
     * @var string
     */
    protected $prevName = '[prev]';

     /**
     * Default next tag name
     * 
     * @var string
     */
    protected $nextName = '[next]';

     /**
     * Default first tag name
     * 
     * @var string
     */
    protected $firstName = '[first]';

     /**
     * Default last tag name
     * 
     * @var string
     */
    protected $lastName = '[last]';

     /**
     * Default url
     * 
     * @var string
     */
    protected $url = CURRENT_CFPATH;

    /**
     * Output type
     * 
     * @var string
     */
    protected $output = 'classic';

    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        $this->config = Config::default('ZN\Pagination\PaginationDefaultConfiguration')::viewObjects('pagination');
    }

    /**
     * Specifies the URL.
     * 
     * @param string $url
     * 
     * @return Paginator
     */
    public function url(String $url) : Paginator
    {
        $this->settings['url'] = $url;

        return $this;
    }

    /**
     * Sets the paging initial value.
     * 
     * @param mixed $start
     * 
     * @return Paginator
     */
    public function start($start) : Paginator
    {
        $this->settings['start'] = $start;

        return $this;
    }

    /**
     * Sets the amount of data to be displayed at one time.
     * 
     * @param int $limit
     * 
     * @return Paginator
     */
    public function limit(Int $limit) : Paginator
    {
        $this->settings['limit'] = $limit;

        return $this;
    }

    /**
     * Pagination usage type.
     * If you select Ajax, ajax needs to be written. 
     * Several data are defined for this.
     * 
     * @param string $type - options[ajax|classic]
     */
    public function type(String $type) : Paginator
    {
        $this->settings['type'] = $type;

        return $this;
    }

    /**
     * Sets the total number of records.
     * 
     * @param int $totalRows
     * 
     * @return Paginator
     */
    public function totalRows(Int $totalRows) : Paginator
    {
        $this->settings['totalRows'] = $totalRows;

        return $this;
    }

    /**
     * Sets the number of page links to be displayed at one time.
     * 
     * @param int $countLinks
     * 
     * @return Paginator
     */
    public function countLinks(Int $countLinks) : Paginator
    {
        $this->settings['countLinks'] = $countLinks;

        return $this;
    }

    /**
     * Change the names of links.
     * 
     * @param string $prev
     * @param string $next
     * @param string $first
     * @param string $last
     * 
     * @return Paginator
     */
    public function linkNames(String $prev, String $next, String $first, String $last) : Paginator
    {
        $this->settings['prevName']  = $prev;
        $this->settings['nextName']  = $next;
        $this->settings['firstName'] = $first;
        $this->settings['lastName']  = $last;

        return $this;
    }

    /**
     * Sets paging's css values.
     * 
     * @param array $css
     * 
     * @return Paginator
     */
    public function css(Array $css) : Paginator
    {
        $this->settings['class'] = $css;

        return $this;
    }

    /**
     * Sets paging's style values.
     * 
     * @param array $style
     * 
     * @return Paginator
     */
    public function style(Array $style) : Paginator
    {
        $this->settings['style'] = $style;

        return $this;
    }

    /**
     * Sets paging's output type.
     * 
     * @param string $type - options[bootstrap|classic]
     * 
     * @return Paginator
     */
    public function output(String $type) : Paginator
    {
        $this->settings['output'] = $type;

        return $this;
    }

    /**
     * Returns the current URL for paging.
     * 
     * @param string $page = NULL
     * 
     * @return string
     */
    public function getURI(String $page = NULL) : String
    {
        return $this->checkGetRequest($page);
    }

    /**
     * Configures all settings of the page.
     * 
     * @param array $cofig = []
     * 
     * @return Paginator
     */
    public function settings(Array $config = []) : Paginator
    {
        foreach( $config as $key => $value )
        {
            $this->$key = $value;
        }        

        $this->class = array_merge($this->config['class'], $this->class ?? []);
        $this->style = array_merge($this->config['style'], $this->style ?? []);

        if( isset($config['url']) && $this->type !== 'ajax' )
        {
            $this->url = Base::suffix(URL::site($config['url']));
        }
        elseif( $this->type === 'ajax' )
        {
            $this->url = 'javascript:;';
        }
        else
        {
            $this->url = CURRENT_CFURL;
        }

        return $this;
    }

    /**
     * Creates the pagination.
     * 
     * @param mixed $start
     * @param array $settings = []
     * 
     * @return string
     */
    public function create($start = NULL, Array $settings = []) : String
    {
        $settings = array_merge($this->config, $this->settings, $settings);

        if( ! empty($settings) )
        {
            $this->settings($settings);
        }

        if( $this->start !== NULL )
        {
            $start = (int) $this->start;
        }

        $page = ''; $numberLinks = '';

         if( empty($start) && ! is_numeric($start) )
        {
            $startPage = ! is_numeric($segment = URI::segment(-1)) ? 0 : $segment;
        }
        else
        {
            $startPage = ! is_numeric($start) ? 0 : $start;
        }

        $this->limit     = $this->limit === 0 ? 1 : $this->limit;
        $perPage         = ceil($this->totalRows / $this->limit);

        if( $this->countLinks > $perPage )
        {
            # Links 
            for( $i = 1; $i <= $perPage; $i++ )
            {
                $page = ($i - 1) * $this->limit;

                if( $i - 1 == floor($startPage / $this->limit) )
                {
                    $currentLink = $this->getStyleClassAttributes('current');
                }
                else
                {
                    $currentLink = $this->getStyleClassLinkAttributes('links');;
                }

                $numberLinks .= $this->getLink($page, $currentLink, $i);
            }
            # Links

            # Prev tag
            if( $startPage != 0 )
            {
                $prevLink = $this->getLink($this->decrementPageRowNumber($startPage), $this->getStyleClassAttributes('prev'), $this->prevName);
            }
            else
            {
                $this->removeLinkFromPagingBar($prevLink);
            }
            # Prev tag

            # Next tag
            if( $startPage != $page )
            {
                $nextLink = $this->getLink($this->incrementPageRowNumber($startPage), $this->getStyleClassAttributes('next'), $this->nextName);
            }
            else
            {
                $this->removeLinkFromPagingBar($nextLink);
            }
            # Next tag

            if( $this->totalRows > $this->limit )
            {
                return $this->getHtmlUlElement($prevLink.' '.$numberLinks.' '.$nextLink);
            }
            else
            {
                return false;
            }
        }
        else
        {
            $lastLink = $this->getLink($this->calculatePageRowNumberForLastLink(), $this->getStyleClassAttributes('last'), $this->lastName);

            $firstLink = $this->getLink(0, $this->getStyleClassAttributes('first'), $this->firstName);

            if( $startPage > 0 )
            {
                $prevLink = $this->getLink($this->decrementPageRowNumber($startPage), $this->getStyleClassAttributes('prev'), $this->prevName);
            }
            else
            {
                $this->removeLinkFromPagingBar($prevLink);
            }

            if( ($startPage / $this->limit) == 0 )
            {
                $pagIndex = 1;
            }
            else
            {
                $pagIndex = floor( $startPage / $this->limit + 1);
            }

            if( $startPage < $this->totalRows - $this->limit )
            {
                $nextLink = $this->getLink($this->incrementPageRowNumber($startPage), $this->getStyleClassAttributes('next'), $this->nextName);
            }
            else
            {
                $this->removeLinkFromPagingBar($nextLink);
                $this->removeLinkFromPagingBar($lastLink);

                $pagIndex = ceil($this->totalRows / $this->limit) - $this->countLinks + 1;
            }

            if( $pagIndex < 1 || $startPage == 0 )
            {
                $this->removeLinkFromPagingBar($firstLink);
            }

            $nPerPage = $this->countLinks + $pagIndex - 1;

            if( $nPerPage >= ceil($this->totalRows / $this->limit) )
            {
                $this->removeLinkFromPagingBar($nextLink);
                $this->removeLinkFromPagingBar($lastLink);

                $nPerPage = ceil($this->totalRows / $this->limit);
            }

            $numberLinks = NULL;

            for( $i = $pagIndex; $i <= $nPerPage; $i++ )
            {
                $page = ($i - 1) * $this->limit;

                if( $i - 1 == floor((int)$startPage / $this->limit) )
                {
                    $currentLink = $this->getStyleClassAttributes('current');
                }
                else
                {
                    $currentLink = $this->getStyleClassLinkAttributes('links');;
                }

                $numberLinks .= $this->getLink($page, $currentLink, $i);
            }

            if( $this->totalRows > $this->limit )
            {
                return $this->getHtmlUlElement($firstLink.' '.$prevLink.' '.$numberLinks.' '.$nextLink.' '.$lastLink);
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * Protected increment page row number
     */
    protected function incrementPageRowNumber($startPage)
    {
        return $startPage + $this->limit;
    }

    /**
     * Protected decrement page row number
     */
    protected function decrementPageRowNumber($startPage)
    {
        return $startPage - $this->limit;
    }

    /**
     * Protected page row number for last link
     */
    protected function calculatePageRowNumberForLastLink()
    {
        $mod = $this->totalRows % $this->limit;

        return ($this->totalRows - $mod ) - ($mod == 0 ? $this->limit : 0);
    }

    /**
     * Protected remove link from paging bar
     */
    protected function removeLinkFromPagingBar(&$data)
    {
        $data = NULL;
    }

    /**
     * protected get style & class link attibutes
     */
    protected function getStyleClassLinkAttributes($type)
    {
        return $this->getClassLinkAttribute($type) . $this->getStyleLinkAttribute($type);
    }

    /**
     * Protected get style class attibutes
     */
    protected function getStyleClassAttributes($type)
    {
        return $this->getClassAttribute($type) . $this->getStyleAttribute($type);
    }

    /**
     * Protected explode request get value
     */
    protected function explodeRequestGetValue()
    {
        return ( $string = explode('?', $_SERVER['REQUEST_URI'])[1] ?? NULL)
               ? '?' . $string
               : '';
    }

    /**
     * Protected check get request
     */
    protected function checkGetRequest($page)
    {
        $this->url .= $this->explodeRequestGetValue();

        if( strstr($this->url, '?') )
        {
            $urlEx = explode('?', $this->url);

            return Base::suffix($urlEx[0]) . $page . '?' . rtrim($urlEx[1], '/');
        }

        return $this->type === 'ajax' ? $this->url : Base::suffix($this->url) . $page;
    }

    /**
     * Protected get link
     */
    protected function getLink($var, $fix, $val)
    {
        return $this->getHtmlLiElement($this->getHtmlAnchorElement($var, $fix, $val), $fix);
    }

    /**
     * Protected get html anchor element
     */
    protected function getHtmlAnchorElement($var, $attr, $val)
    {
        if( $this->output === 'bootstrap' )
        {
            $attr = NULL;
        }

        return '<a href="'.$this->checkGetRequest($var).'"'.$this->getAttributesForAjaxProcess($var).$attr.'>'.$val.'</a>';
    }

    /**
     * Protected get html li element
     */
    protected function getHtmlLiElement($link, $fix)
    {
        if( $this->output === 'bootstrap' )
        {
            return '<li'.$fix.'>' . $link . '</li>';
        }

        return $link;
    }

    /**
     * Protected get html ul element
     */
    protected function getHtmlUlElement($numberLinks)
    {
        if( $this->output === 'bootstrap' )
        {
            return '<ul class="pagination">' . $numberLinks . '</ul>';
        }

        return $numberLinks;
    }

    /**
     * Protected get style link attribute
     */
    protected function getStyleLinkAttribute($var, $type = 'style')
    {
        $l = ( ! empty($this->{$type}[$var]) ) ? $this->{$type}[$var].' ' : '';

        if( $type === 'class' ) $this->classAttribute = $l; else $this->styleAttribute = $l;
   
        return ! empty($l) ? ' '.$type.'="'.trim($l).'"' : '';
    }

    /**
     * Protected get class link attribute
     */
    protected function getClassLinkAttribute($var)
    {
        return $this->getStyleLinkAttribute($var, 'class');
    }

    /**
     * Protected get class attribute
     */
    protected function getClassAttribute($var, $type = 'class')
    {
        return ( $status = trim(( $type === 'class' ? $this->classAttribute : $this->styleAttribute) . $this->{$type}[$var]) ) 
               ? ' '.$type.'="'.$status.'" ' 
               : '';
    }

   /**
     * Protected get style attribute
     */
    protected function getStyleAttribute($var)
    {
        return $this->getClassAttribute($var, 'style');
    }

    /**
     * Protected get attibutes for ajax process
     */
    protected function getAttributesForAjaxProcess($value)
    {
        if( $this->type === 'ajax' )
        {
            return ' prow="'.$value.'" ptype="ajax"';
        }
    }
}
