<?php namespace ZN\Generator;
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
use ZN\Datatype;
use ZN\ErrorHandling\Errors;

class File
{
    /**
     * Keeps Settings
     * 
     * @var array
     */
    protected $settings = [];

    /**
     * Settings
     * 
     * @param array $settings
     * 
     * @return Generate
     */
    public function settings(array $settings) : Generate
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Delete Structure
     * 
     * @param string $name
     * @param string $type = 'controller'
     * @param string $app  = NULL
     * 
     * @return bool
     */
    public function delete(string $name, string $type = 'controller', string $app = NULL) : bool
    {
        if( ! empty($app) )
        {
            $this->settings['application'] = $app; // @codeCoverageIgnore
        }

        $file = $this->path($name, $type);

        if( is_file($file) )
        {
            return unlink($file);
        }

        return false; // @codeCoverageIgnore
    }

    /**
     * Generate Object
     * 
     * @param string $type
     * @param string $name
     * @param array  $settings
     * 
     * @return bool
     */
    public function object(string $type, string $name, array $settings) : bool
    {
        if( ! empty($settings) )
        {
            $this->settings = $settings;
        }

        if( empty($name) )
        {
            $this->error = Errors::message('Error', 'emptyParameter', '1.(name)'); // @codeCoverageIgnore
        }
        
        # Start Generate
        $controller = "<?php".EOL;

        # Object Data
        $this->settings['object'] = $this->settings['object'] ?? 'class';

        # Namespace Data
        $this->namespace($controller, $namespace);

        # Uses Data
        $this->uses($controller);

        # Class Name
        if( ! empty($this->settings['name']) )
        {
            $name = $this->settings['name']; // @codeCoverageIgnore
        }

        $controller .= $this->settings['object']." ".$name;

        # Extends Data
        $this->extends($controller);

        # Implements Data
        $this->implements($controller);

        # Start Body
        $controller .= EOL . "{" . EOL;

        # Traits Data
        $this->traits($controller);

        # Constants Data
        $this->constants($controller);

        # Vars Data
        $this->vars($controller);

        # Functions Data
        $this->functions($controller);

        # Finish Class
        $controller = rtrim($controller, EOL) . EOL . "}";

        # Alias Data
        $this->alias($controller, $namespace);
        
        # File Write
        return $this->write($name, $type, $controller);
    }

    /**
     * Protected Path
     */
    protected function path($name, $type)
    {
        if( empty($this->settings['application']) )
        {
            $this->settings['application'] = Datatype::divide(rtrim(PROJECT_DIR, '/'), '/', -1);
        }

        return PROJECTS_DIR.Base::suffix($this->settings['application']).$this->type($type).Base::suffix($name, '.php');
    }

    /**
     * Protected Write
     * 
     * @param string $name
     * @param string $type
     * @param string $controller
     * 
     * @return bool
     */
    protected function write($name, $type, $controller) : bool
    {
        if( ! empty($name) )
        {
            if( ! empty($this->settings['path']) )
            {
                $filePath = Base::suffix($this->settings['path'], '/') . $name;
            }
            else
            {
                $filePath = $name;
            }
    
            $file = $this->path($filePath, $type);
    
            if( ! is_file($file) )
            {
                if( file_put_contents($file, $controller) )
                {
                    return true;
                }
            }
        }
        
        return false; // @codeCoverageIgnore
    }

    /**
     * Protected Functions
     * 
     * @param string & $controller
     * @param string   $namespace = NULL
     */
    protected function alias(string & $controller, string $namespace = NULL)
    {
        if( ! empty($this->settings['alias']) )
        {
            $controller .= EOL.EOL.'class_alias("'.Base::suffix($namespace, '\\').$name.'", "'.$this->settings['alias'].'");'; // @codeCoverageIgnore
        }
    }

    /**
     * Protected Functions
     * 
     * @param string & $controller
     */
    protected function functions(string & $controller)
    {
        $parameters = '';

        $functions = (array) ($this->settings['functions'] ?? []);

        if( ! empty($functions) ) foreach( $functions as $isKey => $function )
        {
            if( ! empty($function) )
            {
                if( ! is_numeric($isKey) )
                {
                    if( is_array($function) )
                    {
                        foreach( $function as $key => $val )
                        {
                            $subvalue = '';
                            
                            if( ! is_numeric($key) )
                            {
                                $subvalue = $val;
                                $val      = $key;
                            }

                            $vartype = '';

                            if( strstr($val, ' ') )
                            {
                                $varEx = explode(' ', $val);
                                $val = $varEx[1];
                                $vartype = $varEx[0] . ' ';
                            }
                            
                            if( strpos($val, '...') === 0 )
                            {
                                $varprefix = str_replace('...', '...$', $val ?? '');
                                $subvalue  = '';
                            }
                            else
                            {
                                $varprefix = '$'.$val;
                            }

                            $parameters .= $vartype . $varprefix.( ! empty($subvalue) ? ' = '.$subvalue : '').', ';
                        }

                        $parameters = rtrim($parameters, ', ');
                    }

                    $function = $isKey;
                }

                $function = $this->vartype($function);

                $controller .= HT.$function->priority." function {$function->var}({$parameters})".EOL;
                $controller .= HT."{".EOL;
                $controller .= HT.HT."// Your codes...".EOL;
                $controller .= HT."}".EOL.EOL;
            }
        }
    }

    /**
     * Protected Uses
     * 
     * @param string & $controller
     * @param string & $namespace = NULL
     */
    protected function namespace(string & $controller, string & $namespace = NULL)
    {
        if( ! empty($this->settings['namespace']) )
        {
            $namespace   = $this->settings['namespace'];
            $controller .= "namespace ".$namespace.";".EOL.EOL;
        }
    }

    /**
     * Protected Uses
     * 
     * @param string & $controller
     */
    protected function uses(string & $controller)
    {
        if( ! empty($this->settings['use']) )
        {
            foreach( $this->settings['use'] as $key => $use )
            {
                if( is_numeric($key) )
                {
                    $controller .= "use {$use};".EOL;
                }
                else
                {
                    $controller .= "use {$key} as {$use};".EOL;
                }
            }

            $controller .= EOL;
        }
    }

    /**
     * Protected Extends
     * 
     * @param string & $controller
     */
    protected function extends(string & $controller)
    {
        if( ! empty($this->settings['extends']) )
        {
            $controller .= " extends ".$this->settings['extends'];
        }
    }
    
    /**
     * Protected Implements
     * 
     * @param string & $controller
     */
    protected function implements(string & $controller)
    {
        if( ! empty($this->settings['implements']) )
        {
            $controller .= " implements ".( is_array($this->settings['implements'])
                                            ? implode(', ', $this->settings['implements']) // @codeCoverageIgnore
                                            : $this->settings['implements']
                                          );
        }
    }

    /**
     * Protected Traits
     * 
     * @param string & $controller
     */
    protected function traits(string & $controller)
    {
        if( ! empty($this->settings['traits']) )
        {
            if( is_array($this->settings['traits']) ) foreach( $this->settings['traits'] as $trait )
            {
                $controller .= HT."use {$trait};".EOL;
            }
            else
            {
                $controller .= HT."use ".$this->settings['traits'].";".EOL; // @codeCoverageIgnore
            }

            $controller .= EOL;
        }
    }

    /**
     * Protected Contants
     * 
     * @param string & $controller
     */
    protected function constants(string & $controller)
    {
        if( ! empty($this->settings['constants']) )
        {
            foreach( $this->settings['constants'] as $key => $val )
            {
                $controller .= HT."const {$key} = {$val};".EOL;
            }

            $controller .= EOL;
        }
    }

    /**
     * Protected Vars
     * 
     * @param string & $controller
     */
    protected function vars(string & $controller)
    {
        if( ! empty($this->settings['vars']) )
        {
            $var = '';
            foreach( $this->settings['vars'] as $isKey => $var )
            {
                if( ! is_numeric($isKey) )
                {
                    $value = $var;
                    $var   = $isKey;
                }

                $vars = $this->vartype($var);
                $controller .= HT.$vars->priority.' $'.$vars->var.( ! empty($value) ? " = ".$value : '' ).";".EOL;
            }

            $controller .= EOL;
        }
    }

    /**
     * Protected Variable Type
     */
    protected function vartype($variable)
    {
        $this->getEncapsulationType($variable, $priority, $static);

        return (object)
        [
            'priority' => $priority . $static,
            'var'      => $variable
        ];
    }

    /**
     * Protected get encapsulation type
     */
    protected function getEncapsulationType(&$variable, &$priority, &$static)
    {
        $static = NULL;

        if( preg_match('/^((?<type>public|protected|private)(?<access>\sstatic)*\:)/', $variable ?? '', $match) )
        {
            $priority = $match['type'];
            $static   = $match['access'] ?? $static;
            $variable = str_ireplace($match[1], '', $variable ?? '');
        }
        else
        {
            $priority = 'public';
        }
    }

    /**
     * Protected type
     */
    protected function type($type)
    {
        switch( $type )
        {
            case 'model'     : $return = MODELS_DIR;      break;
            case 'controller': $return = CONTROLLERS_DIR; break;
            case 'library'   : $return = LIBRARIES_DIR;   break;
            case 'command'   : $return = COMMANDS_DIR;    break;
        }

        $path = PROJECT_TYPE === 'EIP' ? Datatype::divide(rtrim($return ?? '', '/'), '/', -1) : $return;

        return Base::suffix($path);
    }
}
