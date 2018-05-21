<?php namespace Cronjobs;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use Post;
use Crontab;
use Masterpage;
use Arrays;
use Strings;
use ZN\Config;
use ZN\Model;

class Job extends Model
{
    public static function selectProject()
    {
        Crontab::project(SELECT_PROJECT);
    }

    public static function delete($id)
    {
        Crontab::remove($id);
    }

    public static function create()
    {
        $method = Post::type();
        $metval = Post::typeval();
        $status = false;

        if( ($time = Post::certain()) !== 'none' )
        {
            $status = Crontab::$time();
        }
        elseif( ($time = Post::per()) !== 'none' )
        {
            $status = Crontab::$time(Post::perval());
        }
        else
        {
            if( ($time = Post::minute()) !== 'none' )
            {
                $status = Crontab::$time(Post::minuteval());
            }

            if( ($time = Post::hour()) !== 'none' )
            {
                $status = Crontab::$time(Post::hourval());
            }

            if( ($time = Post::day()) !== 'none' )
            {
                $status = Crontab::$time(Post::dayval());
            }

            if( ($time = Post::month()) !== 'none' )
            {
                $status = Crontab::$time(Post::monthval());
            }
        }

        if( $status === false )
        {
            return Masterpage::error(LANG['crontabTimeError']);
        }
        else
        {
            Crontab::$method($metval);
        }
    }

    public static function list()
    {
        if( Crontab::list() )
        {
            $l    = Crontab::listArray();
            $list = [];

            foreach( $l as $key => $val )
            {
                if( stristr($val, '"'.SELECT_PROJECT.'"') )
                {
                    $timeEx = explode(' ', Strings::divide($val, ' -r'));

                    $timeEx = Arrays::removeLast($timeEx, 2);
                    $time   = implode(' ', $timeEx);
                    $code   = Strings::divide(rtrim($val, ';\''), ';', -1);
                  
                    preg_match('/\s(\/)+(.*?)*php\s/', $val, $path);

                    $list[$key] = [$time, $path[0] ?? Config::services('processor')['path'], $code];
                }
                elseif( stristr($val, 'wget') )
                {
                    $ex = explode(' wget ', $val);

                    $list[$key] = [$ex[0], 'wget', $ex[1]];
                }
            }
        }

        return $list ?? [];
    }
}