<?php namespace Initialize;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ML;
use Folder;
use Arrays;
use Session;
use Cookie;
use ZN\Config;
use ZN\Model;

class Constants extends Model
{
    public static function basic()
    {
        define('DASHBOARD_CONFIG', Config::get('Dashboard'));
        define('MIN_ZN_VERSION', DASHBOARD_CONFIG['minZNVersion']);
        define('DASHBOARD_VERSION', DASHBOARD_CONFIG['version']);
    }

    public static function get()
    {
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
        define('SELECT_PROJECT_DIR', ( ! IS_EXTERNAL ? PROJECTS_DIR . SELECT_PROJECT : 'External' ) . DS);
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
    }
}