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

class Restoration
{
    /**
     * Restore fix
     * 
     * @var string
     */
    protected static $restoreFix = 'Restore';

    /**
     * Redirect request according to route.
     * 
     * @param mixed  $machinesIP
     * @param string $uri
     * 
     * @return void
     */
    public static function routeURI($machinesIP, String $uri)
    {
        if( ! in_array(Request::ipv4(), (array) $machinesIP) && In::requestURI() !== $uri )
        {
            Response::redirect($uri);
        }
    }

    /**
     * Do control machines ip
     * 
     * @param mixed $manipulation = NULL
     * 
     * @return boold
     */
    public static function isMachinesIP($manipulation = NULL)
    {
        $restorationMachinesIP = self::getRestorationConfig()['machinesIP'];

        if( PROJECT_MODE === 'restoration' || $manipulation !== NULL)
        {
            $ipv4 = Request::ipv4();

            if( is_array($restorationMachinesIP) )
            {
                $result = (bool) in_array($ipv4, $restorationMachinesIP);
            }
            elseif( $ipv4 == $restorationMachinesIP )
            {
                $result = true;
            }
            else
            {
                $result = false;
            }
        }
        else
        {
            $result = false;
        }

        return $result;
    }

    /**
     * Restoration mode
     * 
     * @param mixed $settings = NULL
     * 
     * @return void
     */
    public static function mode($settings = NULL)
    {
        $restorable = NULL;

        if( isset($settings['machinesIP']) )
        {
            $restorable = true;

            Config::set('Project', 'restoration', ['machinesIP' => $settings['machinesIP']]);
        }

        if( self::isMachinesIP($settings) === true )
        {
            return false;
        }

        error_reporting(0);

        $restoration = self::getRestorationConfig();

        $restorationPages = $restorable === true && ! isset($settings['functions'])
                          ? [self::getOpenFunction()]
                          : (array) ($settings['functions'] ?? $restoration['pages']);
        
        if( IS::array($restorationPages) )
        {
            $restorationRoutePage = $settings['routePage'] ?? $restoration['routePage'];

            $routePage = strtolower($restorationRoutePage);

            $currentURI = self::getCurrentURI($restorable);

            if( $restorationPages[0] === 'all' )
            {
                if( $currentURI !== $routePage )
                {
                    Response::redirect($restorationRoutePage);
                }
            }

            foreach( $restorationPages as $k => $rp )
            {
                if( strstr($currentURI, strtolower($k)) )
                {
                    Response::redirect($rp);
                }
                else
                {
                    if( strstr($currentURI, strtolower($rp)) )
                    {
                        if( $currentURI !== $routePage )
                        {
                            Response::redirect($restorationRoutePage);
                        }
                    }
                }
            }
        }
    }

    /**
     * Start restoration
     * 
     * @param string $project
     * @param mixed  $directories - options[standart|full|array]
     * 
     * @return bool
     */
    public static function start(String $project, $directories = 'standart')
    {
        if( $directories === 'full' )
        {
            return self::moveProjectRestoreDirectory($project);
        }
        else
        {
            $restoreDirectories = self::getRestoreProjectDirectories($directories);
    
            foreach( $restoreDirectories as $directory )
            {
                $return = self::moveProjectRestoreDirectory($project . DS . $directory);
            }

            return $return;
        }
    }

    /**
     * End restoration
     * 
     * @param string $project
     * @param string $type = NULL - options[NULL|delete]
     * 
     * @return bool
     */
    public static function end(String $project, String $type = NULL)
    {
        self::deleteClassMapIfExists($project);

        $return = self::endRestorationProject($project);

        if( $type === 'delete' )
        {
            return self::deleteRestoreProject($project);
        }

        return $return;
    }

    /**
     * End & delete restoration
     * 
     * @param string $project
     * 
     * @return bool
     */
    public static function endDelete(String $project)
    {
        return self::end($project, 'delete');
    }

    /**
     * Protected get current uri
     */
    protected static function getCurrentURI($restorable)
    {
        return strtolower($restorable === true ? CURRENT_CFUNCTION : rtrim(Request::getActiveURI(), '/'));
    }

    /**
     * Protected get default project directories
     */
    protected static function getRestoreProjectDirectories($directories)
    {
        $restoreDirectories = 
        [
            self::getOnlyDirectoryName(VIEWS_DIR), 
            self::getOnlyDirectoryName(CONTROLLERS_DIR), 
            self::getOnlyDirectoryName(STORAGE_DIR)
        ];

        if( $directories !== 'standart' )
        {
            $restoreDirectories = array_merge($restoreDirectories, $directories);
        }

        return $restoreDirectories;
    }

    /**
     * Protected get only directory name
     */
    protected static function getOnlyDirectoryName($directory)
    {
        return Datatype::divide(rtrim($directory, '/'), '/', -1);
    }

    /**
     * Protected delete class map if exists
     */
    protected static function deleteClassMapIfExists($project)
    {
        # 5.7.2.6[fixed]
        if( file_exists($classMapFile = self::getRestoreProjectDirectory($project) . 'ClassMap.php') )
        {
            unlink($classMapFile);
        }
    }

    /**
     * Protected get restore project directory
     */
    protected static function getRestoreProjectDirectory($project)
    {
        $project = Base::prefix($project, self::$restoreFix);

        return PROJECTS_DIR . Base::suffix($project);
    }

    /**
     * Protected delete restore project
     */
    protected static function deleteRestoreProject($project)
    {
        return Filesystem::deleteFolder(self::getRestoreProjectDirectory($project));
    }

    /**
     * Protected end restoration project
     */
    protected static function endRestorationProject($project)
    {
        return Filesystem::copy(self::getRestoreProjectDirectory($project), PROJECTS_DIR . Base::removePrefix($project, self::$restoreFix));
    }

    /**
     * Protected create project restore directory
     */
    protected static function moveProjectRestoreDirectory($project)
    {
        return Filesystem::copy(PROJECTS_DIR . $project, PROJECTS_DIR . self::$restoreFix . $project);
    }

    /**
     * Protected get open function
     */
    protected static function getOpenFunction()
    {
        return Config::get('Routing', 'openFunction') ?: 'main';
    }

    /**
     * Protected get restoration config
     */
    protected static function getRestorationConfig()
    {
        return Config::get('Project', 'restoration');
    }
}

# Alias Restoration
class_alias('ZN\Restoration', 'Restoration');
