<?php namespace ZN;

class Butcher
{
    /**
     * Protected butchery json 
     * 
     * @var string
     */
    protected $butcheryJson = BUTCHERY_DIR . 'butchery.json';

    /**
     * Run
     * 
     * @return true;
     */
    public function run()
    {
        $this->createButcheryDirectory();
        $this->generateControllers();
        $this->moveAssetsToThemeDirectory();  

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
     * Protected get json object
     */
    protected function getJsonObject()
    {
        return json_decode($this->readJsonFile());
    }

    /**
     * Protected get theme directory name
     */
    protected function getThemeDirectoryName()
    {
        return $this->getJsonObject()->theme ?? 'Default';
    }

    /**
     * Protected get assets
     */
    protected function getAssets()
    {
        return $this->getJsonObject()->assets ?? 'all';
    }  

    /**
     * Protected read json file
     */
    public function readJsonFile()
    {
        if( file_exists($this->butcheryJson) )
        {
            return file_get_contents($this->butcheryJson);
        }
        
        return false;
    }

    /**
     * Protected get HTML files
     */
    public function getHTMLFiles()
    {
        return Filesystem::getFiles(BUTCHERY_DIR, 'html');
    }

    /**
     * Protected get directories
     */
    public function getDirectories()
    {
        return Filesystem::getFiles(BUTCHERY_DIR, 'dir');
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
        if( is_array($getAssets = $this->getAssets()) ) 
        {
            foreach( $getAssets as $file )
            {
                $this->moveAssets($file);
            }
        }
        elseif( $this->getAssets() === 'all' )
        {
            $getAssets = $this->getDirectories();
            
            foreach( $getAssets as $file )
            {
                $this->moveAssets($file);
            }
        }
        else
        {
            $this->moveAssets($this->getAssets());
        }  

        return true;
    }

    /**
     * Protected function
     */
    protected function moveAssets($path)
    {
        $dir  = $path;
        $path = BUTCHERY_DIR . $path;

        Filesystem::copy($path, $this->getThemePath($dir));
    }

    /**
     * Protected get theme path
     */
    protected function getThemePath($directory = NULL)
    {
        return THEMES_DIR . $this->getThemeDirectoryName() . (Base::prefix($directory));
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
                    'functions' => ['main']
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
        $file = BUTCHERY_DIR . $file;
        $viewDirectory = VIEWS_DIR . $controller;

        Filesystem::createFolder($viewDirectory);

        $content = file_get_contents($file);

        preg_match('/<head.*?>(.*?)<\/head>.*?<body.*?>(.*?)<\/body>/is', $content, $match);

        $head = $match[1] ?? false;
        $body = $match[2] ?? false;

        $mainFile = $viewDirectory . '/main.wizard.php';

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