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

use ZN\Model;

class Menus extends Model
{
    public static function defines()
    {
        $menus['versionNotes']  = ['icon' => 'arrow-circle-o-right',   'href' => 'version/notes'];
        $menus['home']          = ['icon' => 'home',       'href' => 'home/main'];
       
        if( self::isDirectory('Models') ) $menus['configs']   = ['icon' => 'cog',        'href' => 'generate/config'];

        if( ! IS_EXTERNAL )
        {
            $menus['controllers']      = ['icon' => 'gears',   'href' => 'generate/controller'];
            $menus['views']            = ['icon' => 'file-code-o',    'href' => 'generate/view'];
            
            if( SELECT_PROJECT !== CURRENT_PROJECT)
            {
                $tools['themeIntegration'] = 'integration';
            }
        }
        
        if( self::isDirectory('Models'   ) ) $menus['models']     = ['icon' => 'database',   'href' => 'generate/model'];
        if( self::isDirectory('Models'   ) ) $menus['migrations'] = ['icon' => 'cubes',      'href' => 'generate/migration'];
        if( self::isDirectory('Libraries') ) $menus['libraries']  = ['icon' => 'book',       'href' => 'generate/library'];
        if( self::isDirectory('Commands' ) ) $menus['commands']   = ['icon' => 'code',       'href' => 'generate/command'];
        if( self::isDirectory('Routes'   ) ) $menus['routes']     = ['icon' => 'repeat',     'href' => 'generate/route'];
        if( self::isDirectory('Languages') ) $tools['languages']  = 'system/language';
        if( self::isDirectory('Starting' ) ) $menus['starting']   = ['icon' => 'renren',     'href' => 'generate/starting'];

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

    public static function isDirectory($directory)
    {
        return is_dir( SELECT_PROJECT_DIR . $directory);
    }
}