<?php namespace ZN;

class Butcher
{
    /**
     * Protected default project file
     * 
     * @var string
     */
    protected $defaultProjectFile = EXTERNAL_FILES_DIR . 'DefaultProject.zip';

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
     * Protected current butchery directory
     * 
     * @var string
     */
    protected $currentButcheryDirectory;

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
     * Protected inc
     * 
     * @var int
     */
    protected $inc = 0;

    /**
     * Protected body parser
     * 
     * @var array
     */
    protected $bodyParser = [];

    /**
     * Protected multiple
     * 
     * @var string
     */
    protected $multiple = NULL;

    /**
     * Magic constructor
     */
    public function __construct()
    {
        $this->lang = Lang::default('ZN\CoreDefaultLanguage')::select('Core');
    }   

    /**
     * Sets default project file.
     * 
     * @param string $path
     * 
     * @return $this
     */
    public function defaultProjectFile(String $path)
    {
        $this->defaultProjectFile = Base::suffix($path, '.zip');

        if( ! file_exists($this->defaultProjectFile) )
        {
            throw new Exception\FileNotFoundException($this->defaultProjectFile);
        }

        return $this;
    }

    /**
     * Sets default project file.
     * 
     * @param string $path
     * 
     * @return $this
     */
    public function location(String $location)
    {
        if( ! in_array($location, ['project', 'external']) )
        {
            throw new Exception\InvalidLocationException;
        }

        $this->location = $this->location;

        return $this;
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
        $this->currentButcheryDirectory = PROJECTS_DIR . $application . '/Butchery/';

        return $this;
    }

    /**
     * Extract themes.
     * 
     * @param string $which    = 'all'     - options[all|{name}]
     * @param string $case     = 'title'   - options[title|lower|slug|normal|{name}]
     * @param string $location = 'project' - options[project|external]
     * @param bool   $force    = false     - options[true|false] 
     */
    public function extract(String $which = 'all', String $case = 'title', String $location = 'project', Bool $force = false)
    {
        $this->openZipFiles(EXTERNAL_BUTCHERY_DIR, true);

        if( $which === 'all' )
        {
            $themes = Filesystem::getFiles(EXTERNAL_BUTCHERY_DIR, ['dir']);

            if( empty($themes) )
            {
                return $this->getLangValue('notFoundExternalButcheryThemes');
            }

            foreach( $themes as $theme )
            {
               $this->runProjectExtract($theme, $case, $force, $location);
            }

            return $this->getLangValue('extractThemeSuccess');
        }
        else
        {
            return $this->runProjectExtract($which, $case, $force, $location);
        }  

        return $this->getLangValue('cantExtractTheme');
    }

    /**
     * Extract themes.
     * 
     * @param string $which = 'all'   - options[all|{name}]
     * @param string $case  = 'title' - options[title|lower|slug|normal|{name}]
     * @param string $location = 'project' - options[project|external]
     */
    public function extractForce(String $which = 'all', String $case = 'title', String $location = 'project')
    {
        return $this->extract($which, $case, $location, true);
    }

    /**
     * Extract themes.
     * 
     * @param string $which = 'all'   - options[all|{name}]
     * @param string $case  = 'title' - options[title|lower|slug|normal|{name}]
     * @param string $location = 'project' - options[project|external]
     */
    public function extractDelete(String $which = 'all', String $case = 'title', String $location = 'project')
    {
        $this->extractDelete = true;

        return $this->extract($which, $case, $location, true);
    }


    /**
     * Run
     * 
     * @param string $theme    = 'Default' - options[{name}|multiple]
     * @param string $location = 'project' - options[project|external]
     * 
     * @return true
     */
    public function run(String $theme = 'Default', String $location = 'project')
    {
        if( $location === 'external' )
        {
            $this->location = $location;
        }

        if( $theme === 'multiple' )
        {
            $this->openZipFiles($this->getCurrentProjectButcheryDirectory());

            if( $directories = Filesystem::getFiles($this->getCurrentProjectButcheryDirectory(), 'dir') )
            {
                foreach( $directories as $directory )
                {
                    $this->themeDirectory = $this->projectDirectoryCase($directory, 'title');

                    $this->multiple = $directory . '/';
    
                    $this->singleRun();
                }
            }  
            else
            {
                return $this->getLangValue('cantMultipleExtractTheme', $this->getCurrentProjectButcheryDirectory());
            }    
        }
        else
        {
            $this->themeDirectory = $theme;
        
            $this->singleRun();
        }

        return $this->getLangValue('extractThemeSuccess');
    }

    /**
     * Protected get lang value
     */
    protected function getLangValue($key, $string = NULL)
    {
        return str_replace('%', $string, $this->lang['butcher:' . $key]);
    }

    /**
     * Protected get current project butchery directory
     */
    protected function getCurrentProjectButcheryDirectory()
    {
        return ($this->currentButcheryDirectory ?? BUTCHERY_DIR) . $this->multiple;
    }

    /**
     * Protected single run.
     */
    protected function singleRun()
    {
        $this->findHTMLFiles($this->getCurrentProjectButcheryDirectory());
        $this->generateControllers();
        $this->moveAssetsToThemeDirectory(); 
    }

    /**
     * Run Delete
     * 
     * @param string $theme = 'Default'
     * @param string $location = 'project' - options[project|external]
     * 
     * @return true
     */
    public function runDelete(String $theme = 'Default', String $location = 'project')
    {
        $return = $this->run($theme, $location);

        Filesystem::deleteFolder($this->getCurrentProjectButcheryDirectory());

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
     * Protected route config
     */
    protected function routeConfig()
    {
        return $this->getApplicationConfig('Routing') ?: ['openController' => 'Home', 'openFunction' => 'main'];
    }

    /**
     * Protected run project extract
     */
    protected function runProjectExtract($theme, $case, $force, $location)
    {
        $this->currentButcheryDirectory = EXTERNAL_BUTCHERY_DIR . $theme . '/';

        $project = $this->projectDirectoryCase($theme, $case);

        if( $this->generateProject($project, $force) )
        {
            $this->application = $project;

            $this->run($project, $location);

            if( $this->extractDelete ?? NULL )
            {
                Filesystem::deleteFolder($this->currentButcheryDirectory);
            }

            return $this->getLangValue('extractThemeSuccess');
        }

        return $this->getLangValue('cantExtractTheme');
    }

    /**
     * Protected generate project
     */
    protected function generateProject($project, $force)
    {
        $source = $this->defaultProjectFile;
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

        $directory = str_replace([' ', '_'], '-', $directory);

        switch( $case )
        {
            case 'slug' : return strtolower($directory);
            case 'title': 
            case 'lower': return $this->mbConvertCase($directory, $case);
            default     : 
            {
                $case = explode(':', $case); $type = $case[1] ?? NULL;
                $name = $case[0];
                
                if( strpos($type, 'inc') === 0 )
                {
                    return $this->setIncrementCase($name, $type);
                }
                elseif( strpos($type, 'rand') === 0 )
                {
                    return $this->randCase($name, $type);
                }

                return $this->incrementCase($name);          
            }
        }
    }

    /**
     * Protected set increment case
     */
    protected function setIncrementCase($case, $type)
    {
        if( preg_match('/inc\[(?<increment>[0-9]+)\]/', $type, $match) )
        {
            if( $this->inc === 0 )
            {
                $this->inc = $match['increment'] ?? 0;
            }
    
            return $case . $this->inc++;
        }

        return $this->incrementCase($case);
    }

    /**
     * Protected rand case
     */
    protected function randCase($case, $type)
    {
        if( preg_match('/rand\[(?<min>[0-9]+)\s*\,\s*(?<max>[0-9]+)\]/', $type, $match) )
        {
            return $case . rand($match['min'] ?? 0, $match['max'] ?? 0);
        }

        return $this->incrementCase($case); 
    }

    /**
     * Protected increment case 
     */
    protected function incrementCase($case)
    {
        static $start = 0;

        $fix = $start === 0 ? NULL : $start;

        $start++; 

        return $case . $fix;
    }
    
    /**
     * Protected MB convert case
     */
    protected function mbConvertCase($string, $type)
    {
        return str_replace(' ', '', mb_convert_case(str_replace('-', ' ', $string), Helper::toConstant($type, 'MB_CASE_')));
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

            if( $this->extractDelete ?? NULL )
            {
                Filesystem::deleteFolder($directory . $zip);
            }
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
        return $this->cleanNumericPrefix
        (
            $this->titleCase
            (
                $this->convertControllerName
                (
                    $this->removeExtension($controller)
                )
            )
        );
    }

    /**
     * Protected convert slug separator
     */
    protected function convertSlugSeparator($string)
    {
        return str_replace([' ', '_', '.'], '-', $string);
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
        
        if( ! file_exists($return) && $dir !== CONFIG_DIR )
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
     * Protected multiple theme directory
     */
    protected function getMultipleThemeDirectory()
    {
        return ($this->multiple ? $this->getThemeDirectoryName() . '/' : NULL);
    }

    /**
     * Protected generate view
     */
    protected function generateView($controller, $file)
    {
        $file = $this->findBaseThemeDirectory . $file;
        
        $viewDirectory = ($viewThemeDirectory = $this->viewsDirectory() . $this->getMultipleThemeDirectory()) . $controller . '/'; 

        Filesystem::createFolder($viewDirectory);

        $content = file_get_contents($file);

        preg_match('/<head.*?>(?<head>.*?)<\/head>.*?<body.*?>(?<body>.*?)<\/body>/is', $content, $match);

        $head = $match['head'] ?? false;
        $body = $match['body'] ?? false;

        if( $body !== false )
        {
            $mainFile = $viewDirectory . $this->routeConfig()['openFunction'].'.wizard.php';

            $this->generateBodyViewContent($mainFile, $body);
        }

        if( $head !== false && $controller === $this->routeConfig()['openController'] )
        {
            $this->createSectionViews($sectionsDirectory);

            $headFile = $sectionsDirectory . 'head.wizard.php';

            if( $this->getMultipleThemeDirectory() !== NULL )
            { 
                $this->generateMultipleHeadPage($headFile);

                $headFile = $viewThemeDirectory . 'head.wizard.php';
            }
            
            $this->generateHeadViewContent($headFile, $head);
        }
    }

    /**
     * Protected generate multiple head page
     */
    protected function generateMultipleHeadPage($file)
    {
        $content = '@view(ZN\Inclusion\Project\Theme::$active . \'/head.wizard.php\')';

        if( ! file_exists($file) || file_get_contents($file) !== $content )
        {
            file_put_contents($file, $content);
        } 
    }

    /**
     * Protected generate head view content
     */
    protected function generateHeadViewContent($file, $content)
    {
        file_put_contents($file, $this->globalPageParser($this->addSlashesToAt($content)));
    }

    /**
     * Protected generate head view content
     */
    protected function generateBodyViewContent($file, $content)
    {
        file_put_contents($file, $this->globalPageParser($this->bodyParser($content)));
    }

    /**
     * Protected body parser
     */
    protected function bodyParser($body)
    {
        return $this->addSlashesToAt(preg_replace_callback('/(?<attribute>(href|action))\=(\"|\')(?<filename>.*?\.html)(\"|\')/', function($link)
        {
            $self = $link['attribute'];

            if( ! IS::url($url = $link['filename']) )
            {
                return str_replace
                (
                    $url, 
                    '{|{ URL::site(\''.$this->convertValidControllerName($url).'\') }|}',
                    $self
                );
            }
            
            return $self;

        }, $body));
    }

    /**
     * Clean comments
     * 
     * @return $this
     */
    public function cleanComments()
    {
        $this->bodyParser['/\<\!\-\-(.*?)\-\-\>/s'] = '';

        return $this;
    }

    /**
     * Protected global parser
     */
    protected function globalPageParser($page)
    {
        $this->bodyParser['/(\.\.\/)+/']    = '//';
        $this->bodyParser['/\{\{/']         = '[{';
        $this->bodyParser['/\}\}/']         = '}]';
        $this->bodyParser['/\{\|\{/']       = '{{';
        $this->bodyParser['/\}\|\}/']       = '}}';

        return preg_replace
        (
            array_keys($this->bodyParser),
            array_values($this->bodyParser),
            $page
        );
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
        $words = explode('-', $this->convertSlugSeparator($file));

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
        Masterpage::headPage(\'Sections/head\')
                  ->bodyPage(\'Sections/body\');
    }
}';

        file_put_contents($this->controllersDirectory() . 'Initialize.php', $initialize);
    }
}