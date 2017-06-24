<?php namespace Project\Controllers;

//------------------------------------------------------------------------------------------------------------
// INITIALIZE
//------------------------------------------------------------------------------------------------------------
//
// Author   : ZN Framework
// Site     : www.znframework.com
// License  : The MIT License
// Copyright: Copyright (c) 2012-2016, znframework.com
//
//------------------------------------------------------------------------------------------------------------

use Folder, Arrays, Form, Config, Route, Validation, Session, Cookie, DB, Restful;

class Initialize extends Controller
{
    //--------------------------------------------------------------------------------------------------------
    // Main
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $params NULL
    //
    //--------------------------------------------------------------------------------------------------------
    public function main(String $params = NULL)
    {
        if( $versions = Restful::post('https://api.znframework.com/statistics/versions') )
        {
            $lastVersionData = $versions[0]->version ?? NULL;
        }

        define('LASTEST_VERSION', $lastVersionData ?? ZN_VERSION);
        define('DASHBOARD_CONFIG', Config::get('Dashboard'));
        define('DASHBOARD_VERSION', DASHBOARD_CONFIG['version']);

        if( strtolower(CURRENT_CONTROLLER) !== 'login' && ! Arrays::valueExists(DASHBOARD_CONFIG['ip'], ipv4()) && ! Session::select('isLogin') )
        {
            redirect('login');
        }

        define('LANG', lang('Dashboard'));

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
        define('NULLTYPES', [DB::null() => DB::null(), DB::notNull() => DB::notNull()]);
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
            Config::set('Database', import($databaseConfigPath));
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
            $menus['controllers']   = ['icon' => 'gears',   'href' => 'generate/controller'];
            $menus['views']         = ['icon' => 'file-code-o',    'href' => 'generate/view'];
        }


        if( IS_CONTAINER === FALSE )
        {
            $menus['models']    = ['icon' => 'database',   'href' => 'generate/model'];
            $menus['migrations']= ['icon' => 'cubes',      'href' => 'generate/migration'];
            $menus['libraries'] = ['icon' => 'book',       'href' => 'generate/library'];
            $menus['commands']  = ['icon' => 'code',       'href' => 'generate/command'];
            $menus['routes']    = ['icon' => 'repeat',     'href' => 'generate/route'];
            $menus['languages'] = ['icon' => 'flag',       'href' => 'system/language'];
            $menus['starting']  = ['icon' => 'renren',     'href' => 'generate/starting'];
        }

        $menus['datatables']    = ['icon' => 'table',      'href' => 'datatables'];
        $menus['grids']         = ['icon' => 'th',         'href' => 'system/grid'];
        $menus['restApi']       = ['icon' => 'exchange',   'href' => 'api/main'];
        $menus['sqlConverter']  = ['icon' => 'refresh',    'href' => 'system/converter'];
        $menus['documentation'] = ['icon' => 'book',       'href' => 'home/docs'];

        if( ! IS_EXTERNAL )
        {
            $menus['systemLogs']    = ['icon' => 'cogs',       'href' => 'system/log'];
            $menus['systemBackup']  = ['icon' => 'floppy-o',   'href' => 'system/backup'];
        }

        $menus['systemInfo']    = ['icon' => 'info',       'href' => 'system/info', 'badge' => (ZN_VERSION < LASTEST_VERSION) ? LASTEST_VERSION : NULL];

        if( IS_CONTAINER === FALSE )
        {
            $menus['terminal']      = ['icon' => 'terminal',   'href' => 'system/terminal'];
        }

        define('MENUS', $menus);
    }
}
