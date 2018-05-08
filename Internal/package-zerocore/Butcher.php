<?php namespace ZN;

class Butcher
{
    /**
     * Protected theme directory
     * 
     * @var string
     */
    protected $themeDirectory = 'Default';

    /**
     * Protected location
     * 
     * @var string
     */
    protected $location = 'project';

    /**
     * Protected fint base theme directory
     * 
     * @var string
     */
    protected $findBaseThemeDirectory = BUTCHERY_DIR;

    /**
     * Protected fint base theme directory
     * 
     * @var string
     */
    protected $externalButcheryDirectory;

    /**
     * Protected application
     * 
     * @var string
     */
    protected $application;

    /**
     * Protected lang
     * 
     * @var array
     */
    protected $lang;

    /**
     * Magic constructor
     */
    public function __construct()
    {
        $this->lang = Lang::default('ZN\CoreDefaultLanguage')::select('CoreButcher');
    }   

    /**
     * Protected route config
     */
    protected function routeConfig()
    {
        return $this->getApplicationConfig('Routing') ?: ['openController' => 'Home', 'openFunction' => 'main'];
    }

    /**
     * Selects project. Only with run and runDelete methods work.
     * 
     * @param string $application
     * 
     * @return $this
     */
    public function application(String $application)
    {
        $this->application = $application;
        $this->externalButcheryDirectory = PROJECTS_DIR . $application . '/Butchery/';

        return $this;
    }

    /**
     * Extract themes.
     * 
     * @param string $which    = 'all'     - options[all|{name}]
     * @param string $case     = 'title'   - options[title|lower|slug|normal|{name}]
     * @param bool   $force    = false     - options[true|false]
     * @param string $location = 'project' - options[project|external]
     */
    public function extract(String $which = 'all', String $case = 'title', Bool $force = false, String $location = 'project')
    {
        $this->openZipFiles(EXTERNAL_BUTCHERY_DIR, true);

        if( $which === 'all' )
        {
            $themes = Filesystem::getFiles(EXTERNAL_BUTCHERY_DIR, ['dir']);

            if( empty($themes) )
            {
                return $this->lang['butcher:notFoundExternalButcheryThemes'];
            }

            foreach( $themes as $theme )
            {
               $this->runProjectExtract($theme, $case, $force, $location);
            }

            return $this->lang['butcher:extractThemeSuccess'];
        }
        else
        {
            return $this->runProjectExtract($which, $case, $force, $location);
        }  

        return $this->lang['butcher:cantExtractTheme'];
    }

    /**
     * Extract themes.
     * 
     * @param string $which = 'all'   - options[all|{name}]
     * @param string $case  = 'title' - options[title|lower|slug|normal|{name}]
     */
    public function extractForce(String $which = 'all', String $case = 'title', String $location = 'project')
    {
        return $this->extract($which, $case, true, $location);
    }

    /**
     * Protected run project extract
     */
    protected function runProjectExtract($theme, $case, $force, $location)
    {
        $this->externalButcheryDirectory = EXTERNAL_BUTCHERY_DIR . $theme . '/';

        $project = $this->projectDirectoryCase($theme, $case);

        if( $this->generateProject($project, $force) )
        {
            $this->application = $project;

            $this->run($project, $location);

            return $this->lang['butcher:extractThemeSuccess'];
        }

        return $this->lang['butcher:cantExtractTheme'];
    }

    /**
     * Protected generate project
     */
    protected function generateProject($project, $force)
    {
        $source = EXTERNAL_FILES_DIR . 'DefaultProject.zip';
        $target = PROJECTS_DIR . $project;

        if( $force === true )
        {
            Filesystem::zipExtract($source, $target);

            return true;
        }
        elseif( ! file_exists($target) )
        {
            Filesystem::zipExtract($source, $target);

            return true;
        }

        return false;
    }

    /**
     * Protected project directory case
     */
    protected function projectDirectoryCase($directory, $case)
    {
        if( $case === 'normal' )
        {
            return $directory;
        }

        static $suffix = 0;

        $directory = str_replace(' ', '-', $directory);

        switch( $case )
        {
            case 'slug' : return strtolower($directory);
            case 'title': 
            case 'lower': return $this->mbConvertCase($directory, $case);
            default     : $fix = $suffix === 0 ? NULL : $suffix;
                          $suffix++; 
                          return $case . $fix;
        }
    }
    
    /**
     * Protected MB convert case
     */
    protected function mbConvertCase($string, $type)
    {
        return str_replace(' ', '', mb_convert_case(str_replace('-', ' ', $string), Helper::toConstant($type, 'MB_CASE_')));
    }

    /**
     * Run
     * 
     * @param string $theme = 'Default'
     * 
     * @return true
     */
    public function run(String $theme = 'Default', String $location = 'project') : Bool
    {
        $this->themeDirectory = $theme;

        if( $location === 'external' )
        {
            $this->location = $location;
        }
        
        $this->findHTMLFiles($this->externalButcheryDirectory ?? BUTCHERY_DIR);
        $this->generateControllers();
        $this->moveAssetsToThemeDirectory();  

        return $this->lang['butcher:extractThemeSuccess'];
    }

    /**
     * Run Delete
     * 
     * @param string $theme = 'Default'
     * 
     * @return true
     */
    public function runDelete(String $theme = 'Default') : Bool
    {
        $return = $this->run($theme);

        Filesystem::deleteFolder($this->externalButcheryDirectory ?? BUTCHERY_DIR);

        return $return;
    }

    /**
     * Protected get theme directory name
     */
    protected function getThemeDirectoryName()
    {
        return $this->themeDirectory;
    }

    /**
     * Protected get HTML files
     */
    protected function getHTMLFiles()
    {
        return Filesystem::getFiles($this->findBaseThemeDirectory, 'html');
    }

    /**
     * Protected get other theme files
     */
    protected function getOtherThemeFiles()
    {
        return Filesystem::getFiles($this->findBaseThemeDirectory, ['dir', 'css', 'js']);
    }

    /**
     * Protected get zip files
     */
    public function openZipFiles($directory, $path = false)
    {
        $zipFiles = Filesystem::getFiles($directory, 'zip');

        if( is_array($zipFiles) && ! empty($zipFiles) ) foreach( $zipFiles as $zip )
        {
            $target = $directory . rtrim($zip, '.zip');

            Filesystem::zipExtract($directory . $zip, $target, $path); 
        }
    }

    /**
     * Protected find HTML Files
     */
    protected function findHTMLFiles($directory = BUTCHERY_DIR)
    {
        $getHTMLFiles = Filesystem::getFiles($directory, 'html');

        if( ! $getHTMLFiles )
        {
            $this->openZipFiles($directory);

            $getThemeDirectories = Filesystem::getFiles($directory, 'dir');

            foreach( $getThemeDirectories as $dir )
            {
                $this->findHTMLFiles($directory . Base::suffix($dir));
            }
        }
        else
        {
            $this->findBaseThemeDirectory = $directory;
        }
    }

    /**
     * Protected write initizalize cotroller
     */
    protected function writeInitializeController()
    {
        $initialize = '<?php namespace Project\Controllers;

class Initialize extends Controller
{
    /**
     * The codes to run at startup.
     * It enters the circuit before all controllers. 
     * You can change this setting in Config/Starting.php file.
     */
    public function main(String $params = NULL)
    {
        # The theme is activated.
        # Location: Resources/Themes/'.$this->getThemeDirectoryName().'/
        Theme::active(\''.$this->getThemeDirectoryName().'\');
        
        # The current settings are being configured.
        Masterpage::title(ucfirst(CURRENT_CONTROLLER))
                    ->headPage(\'Sections/head\')
                    ->bodyPage(\'Sections/body\');
    }
}';

        file_put_contents($this->controllersDirectory() . 'Initialize.php', $initialize);
    }

    /**
     * Protected get project theme directory
     */
    protected function getProjectThemeDirectory()
    {
        return $this->themesDirectory() . $this->getThemeDirectoryName();
    }

    /**
     * Protected move assets to theme directory
     */
    protected function moveAssetsToThemeDirectory()
    {
        $getAssets = $this->getOtherThemeFiles();

        $this->cleanProjectThemeDirectory();
        
        if( is_array($getAssets) ) foreach( $getAssets as $file )
        {
            $this->moveAssets($file);
        }

        return true;
    }

    /**
     * Protected clean project theme directory
     */
    protected function cleanProjectThemeDirectory()
    {
        if( file_exists($getProjectThemeDirectory = $this->getProjectThemeDirectory()) )
        {
            Filesystem::deleteFolder($getProjectThemeDirectory);
        }
    }

    /**
     * Protected function
     */
    protected function moveAssets($path)
    {
        $dir  = $path;
        $path = $this->findBaseThemeDirectory . $path;

        Filesystem::copy($path, $this->getThemePath($dir));
    }

    /**
     * Protected get theme path
     */
    protected function getThemePath($directory = NULL)
    {
        return $this->getProjectThemeDirectory() . (Base::prefix($directory));
    }

    /**
     * Protected clean cache
     */
    protected function cleanCache($path)
    {
        clearstatcache(true, $path);
    }

    /**
     * Protected generate controllers
     */
    protected function generateControllers()
    {
        $htmlFiles = $this->getHTMLFiles();

        $this->writeInitializeController();

        if( is_array($htmlFiles) ) 
        {
            foreach( $htmlFiles as $file )
            {
                $controller = $this->convertValidControllerName($file);

                $this->deletePreviousController($controller);

                $this->generator()->controller($controller,
                [
                    'application' => $this->application ?? CURRENT_PROJECT,
                    'namespace'   => $this->getControllerNamespace(),
                    'functions'   => [$this->routeConfig()['openFunction']],
                    'extends'     => 'Controller'
                ]);

                $this->generateView($controller, $file);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Protected convert valid controller name
     */
    protected function convertValidControllerName($controller)
    {
        return $this->cleanNumericPrefix($this->titleCase($this->convertControllerName($this->removeExtension($controller))));
    }

    /**
     * Protected clean numeric prefix
     */
    protected function cleanNumericPrefix($string)
    {
        return preg_replace('/^[0-9]+/', '', $string);
    }

    /**
     * Protected add slashes to at
     */
    protected function addSlashesToAt($string)
    {
        return str_replace('@', '/@', $string);
    }

    /**
     * Protected views directory
     */
    protected function viewsDirectory($type = 'Views', $dir = VIEWS_DIR)
    {
        if( $this->application !== NULL )
        {
            $return = PROJECTS_DIR . $this->application . '/'.$type .'/'; 
        }
        else
        {
            $return = $dir;
        }
        
        if( ! file_exists($return) )
        {
            Filesystem::createFolder($return);
        }

        return $return;
    }

    /**
     * Protected controllers directory
     */
    protected function controllersDirectory()
    {
        return $this->viewsDirectory('Controllers', CONTROLLERS_DIR);
    }

    /**
     * Protected controllers directory
     */
    protected function getApplicationConfig($file)
    {
        $configFile = $this->viewsDirectory('Config', CONFIG_DIR) . $file . '.php';

        if( file_exists($configFile) )
        {
            return require $configFile;
        }

        return [];
    }
    
    /**
     * Protected themes directory
     */
    protected function themesDirectory()
    {
        if( $this->location === 'project' )
        {
            if( $this->application !== NULL )
            {
                $return = PROJECTS_DIR . $this->application . '/Resources/Themes/';
            }
            else
            {
                $return = THEMES_DIR;
            }          
        }
        else
        {
            return EXTERNAL_THEMES_DIR;
        }   
        
        if( ! file_exists($return) )
        {
            Filesystem::createFolder($return);
        }

        return $return;
    }

    /**
     * Protected generate view
     */
    protected function generateView($controller, $file)
    {
        $file = $this->findBaseThemeDirectory . $file;
        
        $viewDirectory = $this->viewsDirectory() . $controller; 

        Filesystem::createFolder($viewDirectory);

        $content = file_get_contents($file);

        preg_match('/<head.*?>(.*?)<\/head>.*?<body.*?>(.*?)<\/body>/is', $content, $match);

        $head = $match[1] ?? false;
        $body = $match[2] ?? false;

        $mainFile = $viewDirectory . '/'.$this->routeConfig()['openFunction'].'.wizard.php';

        if( $body !== false )
        {
            file_put_contents($mainFile, $this->bodyParser($body));
        }

        if( $head !== false && $controller === $this->routeConfig()['openController'] )
        {
            $this->createSectionViews($sectionsDirectory);

            $headFile = $sectionsDirectory . 'head.wizard.php';
            
            file_put_contents($headFile, $this->addSlashesToAt($head));
        }      
    }

    /**
     * Protected body parser
     */
    protected function bodyParser($body)
    {
        return $this->addSlashesToAt(preg_replace_callback('/href\=\"(.*?\.html)\"/', function($link)
        {
            if( ! IS::url($link[1]) )
            {
                return str_replace
                (
                    $link[1], 
                    '{{ URL::site(\''.$this->convertValidControllerName($link[1]).'\') }}',
                    $link[0]
                );
            }
            
            return $link[0];

        }, $body));
    }

    /**
     * Protected create section views
     */
    protected function createSectionViews(&$sectionsDirectory)
    {
        $sectionsDirectory = $this->viewsDirectory() . 'Sections/';

        if( ! file_exists($sectionsDirectory) )
        {
            Filesystem::createFolder($sectionsDirectory);
            file_put_contents($sectionsDirectory . 'body.wizard.php', '@view');
        }
    }

    /**
     * Protected delete provious controller
     */
    protected function deletePreviousController($controller)
    {
        $this->cleanCache($file = ($this->controllersDirectory() . Base::suffix($controller, '.php')));

        if( is_file($file) )
        {
            @unlink($file);
        }
    }

    /**
     * Protected get controller namespace
     */
    protected function getControllerNamespace()
    {
        return rtrim(PROJECT_CONTROLLER_NAMESPACE, '\\');
    }

    /**
     * Protected convert controller name
     */
    protected function convertControllerName($controller)
    {
        return str_replace(['index'], [$this->routeConfig()['openController']], $controller);
    }

    /**
     * Protected title case
     */
    protected function titleCase($file)
    {
        $words = explode('-', $file);

        $words = array_map(function($data){ return mb_convert_case($data, MB_CASE_TITLE);}, $words);

        return implode('', $words);
    }

    /**
     * Protected generator
     */
    protected function generator()
    {
        return Singleton::class('ZN\Generator\Generate');
    }

    /**
     * Protected remove extension
     */
    protected function removeExtension($file)
    {
        return Filesystem::removeExtension($file);
    }
}