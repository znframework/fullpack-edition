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
    protected $location = THEMES_DIR;

    /**
     * Protected open function
     * 
     * @var string
     */
    protected $openFunction = 'main';

    /**
     * Protected fint base theme directory
     * 
     * @var string
     */
    protected $findBaseThemeDirectory = BUTCHERY_DIR;

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
        $this->openFunction   = Config::get('Routing', 'openFunction');

        if( $location === 'external' )
        {
            $this->location = EXTERNAL_THEMES_DIR;
        }
        
        $this->findHTMLFiles();
        $this->createButcheryDirectory();
        $this->generateControllers();
        $this->moveAssetsToThemeDirectory();  

        return true;
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
        $this->run($theme);

        Filesystem::deleteFolder(BUTCHERY_DIR);

        return true;
    }

    /**
     * Protected create butchery directory
     */
    protected function createButcheryDirectory()
    {
        if( ! file_exists(BUTCHERY_DIR) )
        {
            Filesystem::createFolder(BUTCHERY_DIR);
        }

        return true;
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
    public function getHTMLFiles()
    {
        return Filesystem::getFiles($this->findBaseThemeDirectory, 'html');
    }

    /**
     * Protected get other theme files
     */
    public function getOtherThemeFiles()
    {
        return Filesystem::getFiles($this->findBaseThemeDirectory, ['dir', 'css', 'js']);
    }

    /**
     * Protected get zip files
     */
    protected function openZipFiles($directory)
    {
        $zipFiles = Filesystem::getFiles($directory, 'zip');

        if( is_array($zipFiles) ) foreach( $zipFiles as $zip )
        {
            Filesystem::zipExtract($directory . $zip, $directory);
        }
    }

    /**
     * Protected find HTML Files
     */
    public function findHTMLFiles($directory = BUTCHERY_DIR)
    {
        $getHTMLFiles = Filesystem::getFiles($directory, 'html');

        if( ! $getHTMLFiles )
        {
            $this->openZipFiles($directory);

            $getThemeDirectories = Filesystem::getFiles($directory, ['dir']);

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

        file_put_contents(CONTROLLERS_DIR . 'Initialize.php', $initialize);
    }

    /**
     * Protected move assets to theme directory
     */
    protected function moveAssetsToThemeDirectory()
    {
        $getAssets = $this->getOtherThemeFiles();
        
        if( is_array($getAssets) ) foreach( $getAssets as $file )
        {
            $this->moveAssets($file);
        }

        return true;
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
        return $this->location . $this->getThemeDirectoryName() . (Base::prefix($directory));
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
                $controller = $this->titleCase($this->convertControllerName($this->removeExtension($file)));

                $this->deletePreviousController($controller);

                $this->generator()->controller($controller,
                [
                    'namespace' => $this->getControllerNamespace(),
                    'functions' => [$this->openFunction]
                ]);

                $this->generateView($controller, $file);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Protected generate view
     */
    protected function generateView($controller, $file)
    {
        $file = $this->findBaseThemeDirectory . $file;
        $viewDirectory = VIEWS_DIR . $controller;

        Filesystem::createFolder($viewDirectory);

        $content = file_get_contents($file);

        preg_match('/<head.*?>(.*?)<\/head>.*?<body.*?>(.*?)<\/body>/is', $content, $match);

        $head = $match[1] ?? false;
        $body = $match[2] ?? false;

        $mainFile = $viewDirectory . '/'.$this->openFunction.'.wizard.php';

        if( $body !== false )
        {
            file_put_contents($mainFile, $body);
        }

        if( $head !== false && $controller === 'Home' )
        {
            $this->createSectionViews($sectionsDirectory);

            $headFile = $sectionsDirectory . 'head.wizard.php';
            
            file_put_contents($headFile, $head);
        }      
    }

    /**
     * Protected create section views
     */
    protected function createSectionViews(&$sectionsDirectory)
    {
        $sectionsDirectory = VIEWS_DIR . 'Sections/';

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
        $this->cleanCache($file = (CONTROLLERS_DIR . Base::suffix($controller, '.php')));

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
        return str_replace(['index'], ['Home'], $controller);
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