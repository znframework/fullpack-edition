<?php namespace ZN\DateTime;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\IS;
use ZN\Config;
use ZN\Datatype;
use ZN\Helpers\Rounder;
use ZN\Helpers\Converter;

class DateTimeCommon
{
    /**
     * Keeps datetime config.
     * 
     * @var array
     */
    protected $config;

    /**
     * Keeps Class Name
     * 
     * @var string
     */
    protected $className = 'ZN\DateTime\Date';

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        $this->config = Config::default('ZN\DateTime\DateTimeDefaultConfiguration')
                              ::get('Project');

        setlocale(LC_ALL, $this->config['locale']['charset'], $this->config['locale']['language']);
    }

    /**
     * Protected split upper case
     */
    protected function splitUpperCase($method)
    {
        return  Datatype::splitUpperCase($method);
    }

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $parts = $this->splitUpperCase($method);

        $methodType = $parts[0] ?? NULL;
        $expression = strtolower($parts[1] ?? '') ?? NULL;

        if( $methodType === 'diff' )
        {
            return $this->different($parameters[0], $parameters[1] ?? NULL, $expression, strtolower($parameters[2] ?? $parts[2] ?? ''));
        }
        elseif( in_array($methodType, ['add', 'remove']) )
        {
            return $this->$methodType($parameters[0] ?? NULL, $parameters[1] ?? 1, $expression);
        }
        elseif( $methodType === 'current' )
        {
            return $this->set('{'.ltrim($method, $methodType).'}');
        }

        return false; // @codeCoverageIgnore
    }

    /**
     * Sets locale
     * 
     * 5.7.6[added]
     * 
     * @param string $parameters
     * 
     * @return this
     */
    public function locale(...$parameters)
    {
        setlocale(LC_ALL, ...$parameters);

        return $this;
    }

    /**
     * Sets zone
     * 
     * 5.7.6[added]
     * 
     * @param string $timezone
     * 
     * @return this
     */
    public function zone(string $timezone)
    {
        # Sets the timezone.
        if( IS::timeZone($timezone) )
        {
            date_default_timezone_set($timezone);

            return $this;
        }

        throw new Exception\InvalidTimezoneException(NULL, $timezone);
    }

    /**
     * Compare dates
     * 
     * @param string $value1
     * @param string $condition
     * @param string $value2
     * 
     * @return bool
     */
    public function compare(string $value1, string $condition, string $value2) : bool
    {
        $value1 = $this->toNumeric($value1);
        $value2 = $this->toNumeric($value2);
        
        return version_compare($value1, $value2, $condition);
    }

    /**
     * Turns historical data into numeric data.
     * 
     * @param string $dateFormat
     * @param int    $now = NULL
     * 
     * @return int
     */
    public function toNumeric(string $dateFormat, int $now = NULL) : int
    {
        if( $now === NULL )
        {
            $now = time();
        }

        return strtotime($this->returnDatetime($dateFormat), $now);
    }

    /**
     * Converts time data to readable form.
     * 
     * @param int $time
     * @param string $dateFormat = 'Y-m-d H:i:s'
     * 
     * @return string
     */
    public function toReadable(int $time, string $dateFormat = 'Y-m-d H:i:s') : string
    {
        return $this->returnDatetime($dateFormat, $time);
    }

    /**
     * Calculates between dates.
     * 
     * @param string $input
     * @param string $calculate
     * @param string $output = 'Y-m-d'
     * 
     * @return string
     */
    public function calculate(string $input, string $calculate, string $output = 'Y-m-d') : string
    {
        if( ! preg_match('/^[0-9]/', $input) )
        {
            $input = $this->returnDatetime($input);
        }

        # 5.3.5[added]
        if( get_called_class() === 'ZN\DateTime\Time' && $output === 'Y-m-d' )
        {
            $output = '{Hour}:{minute}:{second}';
        }

        $output = $this->convertPattern($output);

        return $this->returnDatetime($output, strtotime($calculate, strtotime($input)));
    }

    /**
     * Sets the date and time.
     * 
     * @param string $exp 
     * 
     * @return string
     */
    public function set(string $exp) : string
    {
        return $this->returnDatetime($exp);
    }

    /**
     * Protected Convert
     */
    protected function convertPattern($change)
    {
        $chars  = Properties::$setDateFormatChars;

        $chars['{century-}|{cen-}'] = $century = substr(date('Y'), 0, 2);
        $chars['{century}|{cen}']   = $century + 1;
        
        $chars  = Datatype::multikey($chars);

        return str_ireplace(array_keys($chars), array_values($chars), $change ?? '');
    }

    /**
     * Protected Date Time
     */
    protected function returnDatetime($format, $timestamp = NULL)
    {
        if( $timestamp === NULL )
        {
            $timestamp = time();
        }

        return date($this->convertPattern($format), $timestamp);
    }

    /**
     * Protected add day
     */
    protected function add(string $datetime = NULL, int $count = 1, $type = 'day', $signal = '+') : string
    {
        if( ! $this->check((string) $datetime) && is_numeric($datetime) && $count = 1 )
        {
            $count    = $datetime;
            $datetime = $this->default();
        }

        return $this->calculate($datetime ?? $this->default(), $signal . $count . $type);
    }

    /**
     * Protected remove day
     */
    protected function remove(string $datetime = NULL, int $count = 1, $type = 'day') : string
    {
        return $this->add($datetime, $count, $type, '-');
    }

    /**
     * Protected different
     */
    protected function different($date1, $date2, $output, $round = NULL) : Float
    {
        if( $date2 === NULL )
        {
            $date2 = $date1;
            $date1 = $this->default();
        }

        $return = Converter::time($this->toNumeric($date2) - $this->toNumeric($date1), 'second', $output);

        if( ! empty($round) )
        {
            return $this->round($round, $return);
        }

        return $return;
    }

    /**
     * Protected round
     */
    protected function round($round, $return)
    {
        if( in_array($round, ['up', 'down', 'average']) )
        {
            return Rounder::$round($return);
        }
        
        return Rounder::average($return); // @codeCoverageIgnore
    }
}
