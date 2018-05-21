<?php namespace Project\Controllers;

use Folder;
use Arrays;
use Form;
use Config;
use Route;
use Validation;
use Session;
use Cookie;
use DB;
use Restful;
use ML;
use User;
use Redirect;
use ZN\Base, Lang;
use ZN\ZN;

class Initialize extends Controller
{
    /**
     * Initialize Controller
     */
    public function main(String $params = NULL)
    {
        define('DASHBOARD_CONFIG', Config::get('Dashboard'));
        define('MIN_ZN_VERSION', DASHBOARD_CONFIG['minZNVersion']);
        define('DASHBOARD_VERSION', DASHBOARD_CONFIG['version']);

        if( ZN_VERSION < MIN_ZN_VERSION )
        {
            Base::trace(Lang::select('DevtoolsErrors', 'versionError', ['%' => MIN_ZN_VERSION, '#' => ZN_VERSION]));
        }

        if( ! Session::lastversion() )
        {   
            if( $return = Restful::useragent(true)->get('https://api.github.com/repos/znframework/'.(ZN::$projectType === 'EIP' ? 'znframework' : 'fullpack-edition').'/tags') )
            {
                if( ! isset($return->message) )
                {
                    usort($return, function($data1, $data2){ return strcmp($data1->name, $data2->name); });

                    rsort($return);

                    $lastest = $return[0];

                    $lastVersionData = $lastest->name ?? NULL;
                }
                else
                {
                    Masterpage::error($return->message);
                }
            }

            Session::znframework($return ?: []);
            Session::lastversion($lastVersionData ?? ZN_VERSION);
        }
        else
        {
            $return = Session::znframework();
            $lastVersionData = Session::lastversion();
        }

        View::znframework($return);

        define('LASTEST_VERSION', $lastVersionData ?? ZN_VERSION);

        if( strtolower(CURRENT_CONTROLLER) !== 'login' && ! Arrays::valueExists(DASHBOARD_CONFIG['ip'], User::ip()) && ! Session::select('isLogin') )
        {
            Redirect::location('login');
        }

        define('LANG', ML::select());

        $projects           = Folder::files(PROJECTS_DIR, 'dir');
        $projects           = Arrays::addLast($projects, 'External');
        $projects           = Arrays::combine($projects, $projects);
        $default            = PROJECTS_CONFIG['directory']['default'];
        $currentProject     = Session::select('project');
        $currentEditorTheme = Cookie::select('editorTheme');

        if( ! $currentEditorTheme )
        {
            $currentEditorTheme = DASHBOARD_CONFIG['editor']['theme'];
        }

        define('SELECT_EDITOR_THEME', $currentEditorTheme);
        define('PROJECT_LIST', $projects);
        define('SELECT_PROJECT', ! empty($currentProject) ? $currentProject : DEFAULT_PROJECT);
        define('IS_EXTERNAL', SELECT_PROJECT === 'External');

        if( ! IS_EXTERNAL )
        {
            $selectProjectDir = PROJECTS_DIR . SELECT_PROJECT;
        }
        else
        {
            $selectProjectDir = 'External';
        }

        define('SELECT_PROJECT_DIR', $selectProjectDir . DS);
        define('LANGUAGES', ['EN', 'TR']);
        define('IS_CONTAINER', PROJECTS_CONFIG['containers'][SELECT_PROJECT] ?? FALSE);
        define('DATATYPES',
        [
            0 => 'Data Type', 'INT' => 'INT', 'BIGINT' => 'BIGINT',
            'CHAR' => 'CHAR', 'VARCHAR' => 'VARCHAR', 'BLOB' => 'TEXT',
            'ENUM' => 'ENUM', 'DECIMAL' => 'DECIMAL',
            'DATE' => 'DATE', 'DATETIME' => 'DATETIME', 'TIMESTAMP' => 'TIMESTAMP'
        ]);
        define('NULLTYPES', ['NULL ' => 'NULL ', 'NOT NULL ' => 'NOT NULL ']);
        define('DATATYPESCHANGE',
        [
            'STRING' => 'ENUM', 'VAR_STRING' => 'VARCHAR', 'LONG' => 'INT',
            'LONGLONG' => 'BIGINT', 'TINY' => 'CHAR',
            'BLOB' => 'BLOB', 'NEWDECIMAL' => 'DECIMAL'
        ]);
        define('EDITOR_THEMES',
        [
            'ambiance'                  => 'Ambiance',
            'chaos'                     => 'Chaos',
            'chrome'                    => 'Chrome',
            'clouds'                    => 'Clouds',
            'clouds_midnight'           => 'Clouds Midnight',
            'cobalt'                    => 'Cobalt',
            'crimson_editor'            => 'Crimson Editor',
            'dawn'                      => 'Dawn',
            'dreamweaver'               => 'Dreamweaver',
            'eclipse'                   => 'Eclipse',
            'github'                    => 'Github',
            'gob'                       => 'Gob',
            'gruvbox'                   => 'Gruvbox',
            'idle_fingers'              => 'Idle Fingers',
            'iplastic'                  => 'Iplastic',
            'katzenmilch'               => 'Katzenmilch',
            'kr_theme'                  => 'Kr Theme',
            'kuroir'                    => 'Kuroir',
            'merbivore'                 => 'Merbivore',
            'merbivore_soft'            => 'Merbivore Soft',
            'mono_industrial'           => 'Mono Industrial',
            'monokai'                   => 'Monokai',
            'pastel_on_dark'            => 'Pastel On Dark',
            'solarized_dark'            => 'Solarized Dark',
            'solarized_light'           => 'Solarized Light',
            'sqlserver'                 => 'SQLServer',
            'terminal'                  => 'Terminal',
            'textmate'                  => 'Textmate',
            'tomorrow'                  => 'Tomorrow',
            'tomorrow_night'            => 'Tomorrow Night',
            'tomorrow_night_blue'       => 'Tomorrow Night Blue',
            'tomorrow_night_bright'     => 'Tomorrow Night Bright',
            'tomorrow_night_eighties'   => 'Tomorrow Night Eighties',
            'twilight'                  => 'Twilight',
            'vibrant_ink'               => 'Vibrant Ink',
            'xcode'                     => 'Xcode'
        ]);
           
        $databaseConfigPath = SELECT_PROJECT_DIR . 'Config' . DS . 'Database.php';

        if( IS_CONTAINER )
        {
            $databaseConfigPath = str_replace(SELECT_PROJECT, IS_CONTAINER, $databaseConfigPath);
        }

        if( SELECT_PROJECT !== 'External' )
        {
            Config::set('Database', Base::import($databaseConfigPath));
        }

        define('CURRENT_DATABASE', Config::get('Database', 'database')['database']);

        $menus['versionNotes']  = ['icon' => 'arrow-circle-o-right',   'href' => 'version/notes'];
        $menus['home']          = ['icon' => 'home',       'href' => 'home/main'];

        if( IS_CONTAINER === FALSE )
        {
            $menus['configs']   = ['icon' => 'cog',        'href' => 'generate/config'];
        }

        if( ! IS_EXTERNAL )
        {
            $menus['controllers']      = ['icon' => 'gears',   'href' => 'generate/controller'];
            $menus['views']            = ['icon' => 'file-code-o',    'href' => 'generate/view'];
            
            if( SELECT_PROJECT !== CURRENT_PROJECT)
            {
                $tools['themeIntegration'] = 'integration';
            }
        }

        if( IS_CONTAINER === FALSE )
        {
            $menus['models']     = ['icon' => 'database',   'href' => 'generate/model'];
            $menus['migrations'] = ['icon' => 'cubes',      'href' => 'generate/migration'];
            $menus['libraries']  = ['icon' => 'book',       'href' => 'generate/library'];
            $menus['commands']   = ['icon' => 'code',       'href' => 'generate/command'];
            $menus['routes']     = ['icon' => 'repeat',     'href' => 'generate/route'];
            $tools['languages']  = 'system/language';
            $menus['starting']   = ['icon' => 'renren',     'href' => 'generate/starting'];
        }

        $tools['datatables']    = 'datatables';
        $tools['grids']         = 'system/grid';
        $tools['packages'] =      'packages';

        if( PHP_OS === 'Linux' || PHP_OS === 'Unix' )
        {
            $tools['cronjobs']   = 'cronjobs';
        }

        $tools['restApi']       = 'api';
        $tools['sqlConverter']  = 'system/converter';
        $tools['documentation'] = 'home/docs';

        if( ! IS_EXTERNAL )
        {
            $tools['systemLogs']    = 'system/log';
            $tools['systemBackup']  = 'system/backup';
        }

        $menus['systemInfo']  = ['icon' => 'info',       'href' => 'system/info', 'badge' => (ZN_VERSION < LASTEST_VERSION) ? LASTEST_VERSION : NULL];
        $tools['experiments'] = 'experiments';
        $tools['terminal']    = 'system/terminal';

        define('TOOLS', $tools);
        define('MENUS', $menus);
    }
}
