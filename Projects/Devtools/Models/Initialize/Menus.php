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