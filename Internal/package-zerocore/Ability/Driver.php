<?php namespace ZN\Ability;
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
use ZN\Support;
use ZN\Singleton;
use ZN\Exception\UndefinedConstException;

trait Driver
{
    /**
     * protected driver
     * 
     * @var string
     */
    protected $driver;

    /**
     * protected driver name
     * 
     * @var string
     */
    protected $selectedDriverName;

    /**
     * magic constructor
     * 
     * @param string $driver = NULL
     * 
     * @return void
     */
    public function __construct(string $driver = NULL)
    {
        # 5.3.42[added]
        # If the parent has a method of building a class, then that method is introduced.
        if( method_exists(get_parent_class() ?: '', '__construct'))
        {
            parent::__construct(); // @codeCoverageIgnore
        }
        
        # If parent class does not contain driver constant, the operation is stopped.
        if( ! defined('static::driver') )
        {
            throw new UndefinedConstException('[const driver] is required to use the [Driver Ability]!'); // @codeCoverageIgnore
        }

        # 5.3.42|5.4.5|5.6.0[edited]
        $driver = $driver                                         ?? # driver($driver)
                  $this->config['driver']                         ?? # class name driver
                  $this->getDriverNameFromDriverConstant()        ?: # define config
                  $this->getDefaultDriverNameFromDriverConstant() ?: # define default
                  static::driver['options'][0]                    ?? // @codeCoverageIgnore
                  $this->setNullDefaultDriverName();                 # Default driver name is NULL

        # It checks whether the selected driver is a valid driver.
        Support::driver(static::driver['options'], $driver);

        # The selected drive stores its name.
        $this->selectedDriverName = $driver;

        # Drivers should be written with Pascal case notation.
        $driver = ucfirst($driver);

        # If the driver does not contain a namespace, it is called directly.
        if( ! isset(static::driver['namespace']) )
        {
            $this->driver = Singleton::class($driver);
        }
        else
        {
            $this->driver = $this->createSingletonInstanceDriverClass($driver);
        }

        # This ability is used to trigger a method of the parent class in the __construct method.
        if( isset(static::driver['construct']) )
        {
            $construct = static::driver['construct'];

            $this->{$construct}();
        }
    }

    /**
     * Select driver
     * 
     * @param string $driver
     * 
     * @return self
     */
    public function driver(string $driver) : self
    {
        return new self($driver);
    }

    /**
     * Protected set null default driver name.
     */
    protected function setNullDefaultDriverName()
    {
        return 'NULL';
    }

    /**
     * Protected create singleton instance driver class.
     */
    protected function createSingletonInstanceDriverClass($driver)
    {
        return Singleton::class(Base::suffix(static::driver['namespace'], '\\') . $driver . 'Driver');
    }

    /**
     * Protected get driver name from driver constant.
     */
    protected function getDriverNameFromDriverConstant()
    {
        return isset(static::driver['config']) ? Config::get(...explode(':', static::driver['config']))['driver'] : NULL;
    }

    /**
     * Protected get default driver name from driver constant.
     */
    protected function getDefaultDriverNameFromDriverConstant()
    {
        return isset(static::driver['default']) ? get_class_vars(static::driver['default'])['driver'] : NULL;
    }
}