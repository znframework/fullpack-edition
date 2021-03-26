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

interface PaginatorInterface
{
    /**
     * Specifies the URL.
     * 
     * @param string $url
     * 
     * @return Pagination
     */
    public function url(string $url) : Paginator;
    
    /**
     * Sets the paging initial value.
     * 
     * @param mixed $start
     * 
     * @return Pagination
     */
    public function start($start) : Paginator;

    /**
     * Sets the amount of data to be displayed at one time.
     * 
     * @param int $limit
     * 
     * @return Pagination
     */
    public function limit(int $limit) : Paginator;

    /**
     * Pagination usage type.
     * If you select Ajax, ajax needs to be written. 
     * Several data are defined for this.
     * 
     * @param string $type - options[ajax|classic]
     */
    public function type(string $type) : Paginator;

    /**
     * Paging
     * 
     * @param string $paging - options[row|page]
     * 
     * @return Paginator
     */
    public function paging(string $paging) : Paginator;

    /**
     * Sets the total number of records.
     * 
     * @param int $totalRows
     * 
     * @return Pagination
     */
    public function totalRows(int $totalRows) : Paginator;

    /**
     * Sets the number of page links to be displayed at one time.
     * 
     * @param int $countLinks
     * 
     * @return Pagination
     */
    public function countLinks(int $countLinks) : Paginator;

    /**
     * Change the names of links.
     * 
     * @param string $prev
     * @param string $next
     * @param string $first
     * @param string $last
     * 
     * @return Pagination
     */
    public function linkNames(string $prev, string $next, string $first, string $last) : Paginator;

    /**
     * Sets paging's css values.
     * 
     * @param array $css
     * 
     * @return Pagination
     */
    public function css(array $css) : Paginator;

    /**
     * Sets paging's style values.
     * 
     * @param array $style
     * 
     * @return Pagination
     */
    public function style(array $style) : Paginator;

     /**
     * Sets paging's output type.
     * 
     * @param string $type - options[bootstrap|classic]
     * 
     * @return Pagination
     */
    public function output(string $type) : Paginator;

    /**
     * Returns the current URL for paging.
     * 
     * @param string $page = NULL
     * 
     * @return string
     */
    public function getURI(string $page = NULL) : string;

    /**
     * Configures all settings of the page.
     * 
     * @param array $cofig = []
     * 
     * @return Pagination
     */
    public function settings(array $config = []) : Paginator;

    /**
     * Creates the pagination.
     * 
     * @param mixed $start
     * @param array $settings = []
     * 
     * @return string
     */
    public function create($start, array $settings = []) : string;
}
