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

trait BootstrapLayouts
{
    /**
     * Protected bootstrap grid system column
     * 
     * @var string
     */
    protected $bootstrapGridsystemCol = NULL;

    /**
     * Protected bootstrap grid system row
     * 
     * @var string
     */
    protected $bootstrapGridsystemRow = NULL;

    /**
     * Protected bootstrap grid sytem column count
     * 
     * @var int 
     */
    protected $bootstrapGridsystemColumnCount = 0;

    /**
     * Container fluid
     * 
     * @return this
     */
    public function fluid()
    {
        $this->bootstrapContainerDivElementAttributes = 'container-fluid';

        return $this;
    }

    /**
     * Start container div
     * 
     * @return string
     */
    public function startContainerDiv()
    {
        return $this->createStartDivElement('container');
    }

    /**
     * Start fluid container div
     * 
     * @return string
     */
    public function startFluidContainerDiv()
    {
        return $this->createStartDivElement('container-fluid');
    }

    /**
     * Start row div
     * 
     * @return string
     */
    public function startRowDiv()
    {
        return $this->createStartDivElement('row');
    }

    /**
     * Start column div
     * 
     * @return string
     */
    public function startColumnDiv($size)
    {
        return $this->createStartDivElement('col-' . $size);
    }

    /**
     * End div
     * 
     * @return string
     */
    public function endDiv()
    {
        return '</div>' . PHP_EOL;
    }

    /**
     * Protected bootstrap column
     */
    protected function bootstrapColumn($content, $match)
    {
        $parts = $this->getGridsystemColumMethodParts($match);

        $this->bootstrapGridsystemCol .= $this->class($this->getGridsytemColumnClass($parts))->div($content);

        $this->bootstrapGridsystemColumnCount += (int) $parts['number'];

        if( $this->bootstrapGridsystemColumnCount === 12 )
        {
            $this->bootstrapGridsystemRow .= $this->class('row')->div($this->bootstrapGridsystemCol ?: '');

            $this->bootstrapGridsystemCol = '';

            $this->bootstrapGridsystemColumnCount = 0;
        } 
    } 

    /**
     * Protected is bootstrap column
     */
    protected function isBootstrapColumn($method, &$match)
    {
        return preg_match('/col(?<type>[a-z][a-z])(?<number>[0-9]{1,})*/', $method, $match);
    }   

    /**
     * Protected get grid system column method parts
     */
    protected function getGridsystemColumMethodParts($match)
    {
        return ['name' => 'col', 'type' => $match['type'], 'number' => $match['number'] ?? 1];
    }

    /**
     * Protected get grid system column class
     */
    protected function getGridsytemColumnClass($parts)
    {
        return implode('-', $parts);
    }

    /**
     * Protected get bootstrap grid system
     */
    protected function getBootstrapGridsystem()
    {
        return $this->bootstrapGridsystemRow ?: $this->bootstrapGridsystemCol ?: '';
    }

    /**
     * Protected create bootstrap grid system
     */
    protected function createBootstrapGridsystem()
    {
        $return = (string) $this->class($this->bootstrapContainerDivElementAttributes ?? 'container')->div($this->getBootstrapGridsystem());

        $this->bootstrapGridsystemRow = '';

        return $return;
    }

    /**
     * Protected create start div element
     */
    protected function createStartDivElement($class)
    {
        return '<div class="' . $class . '">' . PHP_EOL;
    }
}
