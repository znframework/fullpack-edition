<?php namespace ZN;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Comparison;

class Tester
{
    /**
     * Keeps class name
     * 
     * @var string
     */
    protected $class;

    /**
     * Keeps methods
     * 
     * @var array
     */
    protected $methods;

    /**
     * Keeps compares
     * 
     * @var array
     */
    protected $compares;

    /**
     * Get result
     * 
     * @var string
     */
    protected $result;

    /**
     * Total elapsed time
     * 
     * @var float
     */
    protected $totalElasedTime;

    /**
     * Total memory usage
     * 
     * @var float
     */
    protected $totalMemoryUsage;

    /**
     * Total file count
     * 
     * @var int
     */
    protected $totalFileCount;

    /**
     * Total correct count
     * 
     * @var int
     */
    protected $totalCorrectCount;

    /**
     * Get arguments
     * 
     * @var array
     */
    protected $arguments;

    /**
     * Gets core analysis.
     * 
     * 5.7.3[added]
     * 
     * @param string $path
     * 
     * @return array
     */
    public function analysis(String $path = NULL)
    {
        if( $path === NULL )
        {
            switch( ZN::$projectType )
            {
                case 'SE' :
                case 'OE' :
                case 'EIP': $path = 'vendor/znframework'; break;
                case 'FE' : $path = 'Internal';           break;
            }
        }

        $path = Base::suffix($path);

        $files      = Filesystem::getRecursiveFiles($path, true);
        $packages   = preg_grep('/package\-/', Filesystem::getFiles($path));
        $facades    = array_map('ZN\Filesystem::removeExtension', Filesystem::getFiles($path . 'package-zerocore/Facades/'));

        $infos            = [];
        $totalMethods     = 0;
        $totalTestMethods = 0;
        $tests            = [];

        foreach( $files as $file )
        {
            $info = Autoloader::tokenClassFileInfo($file);

            $namespace = $info['namespace'] ?? NULL;
            $class     = $info['class']     ?? NULL;

            if( ! empty($namespace) && ! empty($class) )
            {
                $fullClassName = $namespace . '\\' . $class;

                $methods = preg_grep('/^[a-z]/i', $getClassMethods = get_class_methods($fullClassName));
                
                $isTestClass = strstr($fullClassName, $testsFix = '\\Tests\\');
                $isNotClass  = preg_match('/\w+(Interface|Exception)$/', $fullClassName);

                if( ! $isNotClass && ! $isTestClass && ($methodCount = count($methods)) > 0 )
                {
                    $totalMethods += $methodCount;
                    $infos['classes'][$fullClassName] = $methods;
                } 
                elseif( $isTestClass )
                {
                    $unit = [];

                    if( defined("$fullClassName::unit") )
                    {
                        $unit = $fullClassName::unit;
                    }

                    $unit['class']   = $unit['class'] ?? str_replace($testsFix, NULL, $fullClassName); 
                    $unit['methods'] = ($unit['methods'] ?? []) + preg_grep('/^\_\w+/', (array) $getClassMethods); 

                    $totalTestMethods += count($unit['methods'] ?? false);
                    $tests[$fullClassName] = $unit;
                }
            }
        }

        $infos['tests']               = $tests;
        $infos['facades']             = $facades;
        $infos['packages']            = $packages;       
        $infos['totalClasses']        = $totalFiles = count($files);
        $infos['totalMethods']        = $totalMethods;
        $infos['totalTestMethods']    = $totalTestMethods;
        $infos['testProgressPercent'] = round(($totalTestMethods / $totalMethods) * 100, 2);
   
        return (object) $infos;
    }

    /**
     * Defines class name.
     * 
     * @param string $class
     * 
     * @return Tester
     */
    public function class(String $class) : Tester
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Defines compares.
     * 
     * @param string $compares
     * 
     * @return Tester
     */
    public function compares(Array $compares) : Tester
    {
        $this->compares = $compares;

        return $this;
    }

    /**
     * Defines class methods.
     * 
     * @param array $methods
     * 
     * @return Tester
     */
    public function methods(Array $methods) : Tester
    {
        $this->arguments = debug_backtrace(0)[0]['args'][0];

        $this->methods = $methods;

        return $this;
    }

    /**
     * Start unit test
     * 
     * @param void
     * 
     * @return void
     */
    public function start()
    {
        $this->result = NULL;

        $index = 0;
        
        foreach( $this->methods as $method => $parameters )
        {
            $method = explode(':', $origin = $method)[0];

            Comparison\Testing::start($origin);
            $returnValue = Singleton::class($this->class)->$method(...$parameters);
            Comparison\Testing::end($origin);

            $this->_output($this->class, $origin, gettype($returnValue), $returnValue, $this->compares[$origin] ?? 'NULL');

            $index++;
        }

        $this->_outputBottom($index);
        $this->_startDefaultVariables();
    }

    /**
     * Show result
     * 
     * @param void
     * 
     * @return string
     */
    public function result()
    {
        return Inclusion\Template::use('UnitTests/Output', ['input' => $this->result]);
    }

    /**
     * Output
     * 
     * @param string $class
     * @param string $method
     * @param string $returnType
     * @param string $returnValue
     * 
     * @return void
     */
    protected function _output($class, $method, $returnType, $returnValue, $isResultCorrect)
    {
        $elapsedTime      = Comparison\ElapsedTime::calculate($method);
        $calculatedMemory = Comparison\MemoryUsage::calculate($method);
        $usedFileCount    = Comparison\FileUsage::count($method);
        $returnType       = ucfirst($returnType);

        $this->totalElasedTime   += $elapsedTime;
        $this->totalMemoryUsage  += $calculatedMemory;
        $this->totalFileCount    += $usedFileCount;
        $this->totalCorrectCount += (int) $isResultCorrect;

        $param = $this->arguments[$method];

        $param = array_map(function($data)
        {
            if( ! is_scalar($data) )
            {
                return gettype($data);
            }
            elseif( is_string($data) )
            {
                return "'" . $data . "'";
            }

            return $data;
        }, $param);

        $this->result .= Inclusion\Template::use('UnitTests/ResultTable', 
        [
            'class'       => $class,
            'method'      => $method . '(' . implode(', ', $param) . ')',
            'returnType'  => $returnType,
            'returnValue' => is_scalar($returnValue) ? $returnValue : $returnType,
            'isResultCorrect' => $isResultCorrect,
            'elapsedTime' => $elapsedTime,
            'index'       => str_replace('\\', '-', $class) . '-' . str_replace(':', NULL, $method)
        ], true);
    }

    /**
     * protected output table bottom
     * 
     * @param int $index
     * 
     * @return void
     */
    protected function _outputBottom($index)
    {
        $this->result .= Inclusion\Template::use('UnitTests/TotalTable', 
        [

            'elapsedTime'       => $this->totalElasedTime,
            'memoryUsage'       => $this->totalMemoryUsage,
            'totalFileCount'    => $this->totalFileCount,
            'totalCorrectCount' => $this->totalCorrectCount,
            'index'             => $index
        ], true);

        $this->_defaultVariables();
    }

    /**
     * Default variables
     * 
     * @param void
     * 
     * @return void
     */
    protected function _defaultVariables()
    {
        $this->totalElasedTime  = NULL;
        $this->totalMemoryUsage = NULL;
        $this->totalFileCount   = NULL;
    }

    /**
     * Default start variables
     * 
     * @param void
     * 
     * @return void
     */
    protected function _startDefaultVariables()
    {
        $this->class   = NULL;
        $this->methods = [];
    }
}
