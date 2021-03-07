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
use ZN\Ability\Revolving;

class Paginator implements PaginatorInterface
{
    use Revolving;

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
        # The configuration arrays are merge.
        $settings = array_merge($this->config, $this->settings, $settings);

        # If the configuration is present, it is rearranged.
        if( ! empty($settings) )
        {
            $this->settings($settings);
        }

        # Gets information about which recording to start the paging process.
        $startRowNumber = $this->getStartRowNumber($start);

        # If the limit is set to 0, 1 is accepted.
        $this->limit = $this->limit === 0 ? 1 : $this->limit;
        
        # Generate pagination bar.
        if( $this->isPaginationBar() )
        {
            $return = $this->isBasicPaginationBar() ? $this->createBasicPaginationBar($startRowNumber) : $this->createAdvancedPaginationBar($startRowNumber);

            $this->defaultVariables('all', true);

            return $return;
        }

        return false;
    }

    /**
     * Protected create basic pagination bar
     */
    protected function createBasicPaginationBar($startRowNumber)
    {
        # Add prev link.
        if( $this->isPrevLink($startRowNumber) )
        {
            $this->addPrevLink($startRowNumber, $prevLink);
        }
        # Remove prev link.
        else
        {
            $this->removeLinkFromPagingationBar($prevLink);
        }

        # Generate pagination bar.
        return $this->generatePaginationBar($prevLink, $this->getNumberLinks($this->getPerPage(), $startRowNumber));
    }

    /**
     * Protected create advanced pagination bar
     */
    protected function createAdvancedPaginationBar($startRowNumber)
    {
        # Add prev link.
        if( $this->isAdvancedPrevLink($startRowNumber) )
        {
            $this->addPrevLink($startRowNumber, $prevLink);
        }
        # Remove prev link.
        else
        {
            $this->removeLinkFromPagingationBar($prevLink);
        }

        # Add first, next, last links.
        if( $this->isAdvancedNextLink($startRowNumber) )
        {
            $this->addNextLink($startRowNumber, $nextLink);
            $this->addLastLink($lastLink);
            
            $pageIndex = $this->getPageIndex($startRowNumber);
        }
        # Remove next, last links.
        else
        {
            $this->removeLinkFromPagingationBar($nextLink);
            $this->removeLinkFromPagingationBar($lastLink);

            $pageIndex = $this->getPageIndexWithoutNextLinks();
        }
        
        # On the first page, remove the first link.
        if( $this->isFirstLink($startRowNumber, $pageIndex) )
        {
            $this->removeLinkFromPagingationBar($firstLink);
        }
        else
        {
            $this->addFirstLink($firstLink);
        }

        $advancedPerPage = $this->getAdvancedPerPage($pageIndex, $nextLink, $lastLink);
        
        return $this->generatePaginationBar($firstLink, $prevLink, $this->getNumberLinks($advancedPerPage, $startRowNumber, $pageIndex), $nextLink, $lastLink);
    }

    /**
     * Protected implode links
     */
    protected function implodeLinks(...$links)
    {
        return implode(' ', $links);
    }

    /**
     * Protected get page index without next linkx
     */
    protected function getPageIndexWithoutNextLinks()
    {
        return $this->getPerPage() - $this->countLinks + 1;
    }

    /**
     * Protected get page index
     */
    protected function getPageIndex($startRowNumber)
    {
        if( ($startRowNumber / $this->limit) == 0 )
        {
            return 1;
        }
        
        return floor( $startRowNumber / $this->limit + 1);
    }

    /**
     * Protected get first link
     */
    protected function addFirstLink(&$firstLink)
    {
        $firstLink = $this->getLink(0, $this->getStyleClassAttributes('first'), $this->firstName);
    }

    /**
     * Protected get last link
     */
    protected function addLastLink(&$lastLink)
    {
        $lastLink = $this->getLink($this->calculatePageRowNumberForLastLink(), $this->getStyleClassAttributes('last'), $this->lastName);
    }

    /**
     * Protected get prev link
     */
    protected function addPrevLink($startRowNumber, &$prevLink)
    {
        $prevLink = $this->getLink($this->decrementPageRowNumber($startRowNumber), $this->getStyleClassAttributes('prev'), $this->prevName);
    }

    /**
     * Protected get advanced next link
     */
    protected function addNextLink($startRowNumber, &$nextLink)
    {
        $nextLink = $this->getLink($this->incrementPageRowNumber($startRowNumber), $this->getStyleClassAttributes('next'), $this->nextName);
    }

    /**
     * Protected get start row number
     */
    protected function getStartRowNumber($start)
    {
        if( $this->start !== NULL )
        {
            $start = (int) $this->start;
        }

        if( empty($start) && ! is_numeric($start) )
        {
            return ! is_numeric($segment = explode('?', URI::segment(-1))[0]) ? 0 : $segment;
        }
        
        return ! is_numeric($start) ? 0 : $start;
    }

    /**
     * Protected is basic pagination bar
     */
    protected function isBasicPaginationBar()
    {
        return $this->countLinks > $this->getPerPage();
    }

    /**
     * Protected is pagination bar
     */
    protected function isPaginationBar()
    {
        return $this->totalRows > $this->limit;
    }

    /**
     * Protected advanced per page.
     */
    protected function getAdvancedPerPage($pageIndex, &$nextLink, &$lastLink)
    {
        $perPage = $this->countLinks + $pageIndex - 1;

        if( $perPage >= ($getPerPage = $this->getPerPage()) )
        {
            $this->removeLinkFromPagingationBar($nextLink);
            $this->removeLinkFromPagingationBar($lastLink);

            $perPage = $getPerPage;
        }

        return $perPage;
    }

    /**
     * Protected get per page
     */
    protected function getPerPage()
    {
        return ceil($this->totalRows / $this->limit);
    }

    /**
     * Protected is first link
     */
    protected function isFirstLink($startRowNumber, $pageIndex)
    {
        return $pageIndex < 1 || $startRowNumber == 0;
    }

    /**
     * Protected is advanced prev link
     */
    protected function isAdvancedPrevLink($startRowNumber)
    {
        return $startRowNumber > 0;
    }

    /**
     * Protected is prev link
     */
    protected function isPrevLink($startRowNumber)
    {
        return $startRowNumber != 0;
    }

    /**
     * Protected is advanced next link
     */
    protected function isAdvancedNextLink($startRowNumber)
    {
        return $startRowNumber < $this->totalRows - $this->limit;
    }

    /**
     * Protected is next link
     * 
     * @codeCoverageIgnore
     */
    protected function isNextLink($perPage, $startRowNumber)
    {
        return $startRowNumber < (($perPage - 1) * $this->limit);
    }

    /**
     * Protected get number links
     */
    protected function getNumberLinks($perPage, $startRowNumber, $startIndexNumber = 1)
    {
        $links       = NULL;
        $numberLinks = NULL;
        $lastPage    = ceil($this->settings['totalRows'] / $this->limit);  
        $countLinks  = $this->settings['countLinks'];
        $current     = floor((int) $startRowNumber / $this->limit);
        $startIndexNumber = $current + 1;

        if( $countLinks % 2 == 0 )
        {
            $countLinks += 1;
        }

        $middle = ceil($countLinks / 2);
        $step   = $middle - 1;
        
        if( $lastPage == $perPage )
        {   
            if( $this->isAdvancedNextLink($startRowNumber) )
            {
                $this->addNextLink($startRowNumber, $nextLink);
                $this->addLastLink($lastLink);
                
                $links = $nextLink . $lastLink;
            }

            $startIndexNumber = $startIndexNumber - $step;
            
            $progressCount = $lastPage - $current - $step - 1;

            if( $progressCount >= 0 )
            {
                $perPage = $perPage - $progressCount;
            }     
            else
            {
                $startIndexNumber += $progressCount;
            }
        }
        else if( $startIndexNumber <= $middle )
        {
            $progressCount = $step - ($middle - $startIndexNumber);

            $perPage = $perPage - $progressCount;

            $startIndexNumber = 1;
        }
        else
        {
            $startIndexNumber = $startIndexNumber - $step;
            $perPage = $perPage - $step;
        }

        if( $startIndexNumber < 1 )
        {
            $startIndexNumber = 1;
        }

        for( $i = $startIndexNumber; $i <= $perPage; $i++ )
        {
            $page = ($i - 1) * $this->limit;

            if( $i - 1 == $current )
            {
                $currentLink = $this->getStyleClassAttributes('current');
            }
            else
            {
                $currentLink = $this->getStyleClassLinkAttributes('links');;
            }

            $numberLinks .= $this->getLink($page, $currentLink, $i);
        }

        return $numberLinks . $links;
    }

    /**
     * Protected increment page row number
     */
    protected function incrementPageRowNumber($startRowNumber)
    {
        return $startRowNumber + $this->limit;
    }

    /**
     * Protected decrement page row number
     */
    protected function decrementPageRowNumber($startRowNumber)
    {
        return $startRowNumber - $this->limit;
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
    protected function removeLinkFromPagingationBar(&$data)
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
            $attr = ' class="page-link"';
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
            $fix = preg_replace('/class\=\"(.*?)\"/', 'class="page-item $1"', $fix);

            return '<li'.$fix.'>' . $link . '</li>';
        }

        return $link;
    }

    /**
     * Protected get html ul element
     */
    protected function generatePaginationBar(...$numberLinks)
    {
        $links = $this->implodeLinks(...$numberLinks);
        
        if( $this->output === 'bootstrap' )
        {
            return '<ul class="pagination">' . $links . '</ul>';
        }

        return $links;
    }

    /**
     * Protected get style link attribute
     */
    protected function getStyleLinkAttribute($var, $type = 'style')
    {
        $getAttribute = ( ! empty($this->{$type}[$var]) ) ? $this->{$type}[$var] . ' ' : '';

        if( $type === 'class' ) 
        {
            $this->classAttribute = $getAttribute; 
        }
        else 
        {
            $this->styleAttribute = $getAttribute;
        }
   
        return $this->createAttribute($getAttribute, $type);
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
        $status = trim(( $type === 'class' ? $this->classAttribute : $this->styleAttribute) . $this->{$type}[$var]);

        return $this->createAttribute($status, $type);
    }

    /**
     * Protcted create attribute
     */
    protected function createAttribute($condition, $key, $value = NULL)
    {
        return ! empty($condition) ? ' ' . $key . '="' . trim($value ?? $condition) . '"' : '';
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
            return ' prow="' . $value . '" ptype="ajax"';
        }
    }
}
