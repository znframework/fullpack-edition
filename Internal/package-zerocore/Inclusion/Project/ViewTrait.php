<?php namespace ZN\Inclusion\Project;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Singleton;

trait ViewTrait
{
    /**
     * Keep data
     * 
     * @var array
     */
    public static $data = [];

    /**
     * Usable view methods
     * 
     * @var array
     */
    protected static $usableViewMethods =
    [
        'page', 'view', 'cview', 'template', 'something'
    ];

    /**
     * Usable resources methods
     * 
     * @var array
     */
    protected static $usableResourcesMethods =
    [
        'script', 'style', 'font', 'theme', 'plugin'
    ];

    /**
     * Magic call static
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return self
     */
    public static function __callStatic($method, $parameters)
    {
        if( ($return = self::call($method, $parameters)) !== NULL )
        {
            return $return;
        }

        return new self;
    }

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return $this
     */
    public function __call($method, $parameters)
    {
        if( ($return = self::call($method, $parameters)) !== NULL )
        {
            return $return;
        }

        return $this;
    }

    /**
     * Get method & parameters
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return void
     */
    protected static function call($method, $parameters)
    {
        # If the invoked method does not contain a parameter, it returns the current value.
        if( empty($parameters) )
        {
            return self::$data[$method] ?? false;
        }

        # If the parameter of the invoked method contains a measurable value, 
        # a special operation is executed.
        if( self::isImportMethod($parameters[0], $resolve) )
        {
            # The other parameters are used like the parametric array of the import method.
            $getImportMethodParameters = self::getImportMethodParameters($parameters);

            # If the inclusion method is one of the following 4 methods;
            # [cview|page|view|something|template]
            # If no data is sent to the import method, 
            # the second parameter of the method is set to NULL.
            if( in_array($getImportMethod = $resolve['method'], self::$usableViewMethods) && ! is_array($getImportMethodParameters[0]) )
            {
                array_unshift($getImportMethodParameters, NULL);
            }

            # Getting the contents of the included file.
            self::$data[$method] = $isImportMethodContent = self::getImportMethod($getImportMethod, $resolve['parameter'], $getImportMethodParameters);
        }

        # If the included file is not valid or has no content, 
        # the parameter is assumed to be a normal value.
        if( empty($isImportMethodContent) )
        {
            self::$data[$method] = $parameters[0];
        }

        # Returning NULL resumes object transfer. The return value is assumed to be $this.
        return NULL;
    }

    /**
     * Protected is import method
     */
    protected static function isImportMethod($parameter, &$match)
    {
        return is_scalar($parameter) && preg_match
        (
            '/^(?<method>' . implode('|', self::$usableViewMethods + self::$usableResourcesMethods) . ')\:(?<parameter>.*)/', 
            $parameter, 
            $match
        );
    }

    /**
     * Protected get import method
     */
    protected static function getImportMethod($getImportMethod, $firstParameter, $getOtherParameters)
    {
        return Singleton::class('ZN\Import')->$getImportMethod($firstParameter, ...$getOtherParameters);
    }

    /**
     * Protected get import method parameters
     */
    protected static function getImportMethodParameters($parameters)
    {
        return Singleton::class('ZN\DataTypes\Collection')
                        ->data($parameters)
                        ->removeFirst()
                        ->addLast(true)
                        ->get();
    }
}
