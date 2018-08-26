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


trait OutputElements
{
    /**
     * Protected output element
     * 
     * @var string
     */
    protected $outputElement = NULL;

    /**
     * Magic to string
     * 
     * @return string
     */
    public function __toString()
    {
        if( $outputElement = $this->outputElement )
        {
            $this->outputElement = NULL;

            return $outputElement;
        }
        elseif( $this->getBootstrapGridsystem() )
        {
            if( is_string($return = $this->createBootstrapGridsystem()) )
            {
                return $return;
            } 
        }

        return '';
    }
}
