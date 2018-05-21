<?php namespace Initialize;

use Session;
use Restful;
use Masterpage;
use View;
use ZN\ZN;
use ZN\Base;
use ZN\Lang;
use ZN\Model;

class VersionControl extends Model
{
    public static function throw()
    {
        if( ZN_VERSION < MIN_ZN_VERSION )
        {
            Base::trace(Lang::select('DevtoolsErrors', 'versionError', ['%' => MIN_ZN_VERSION, '#' => ZN_VERSION]));
        }
    }

    public static function getLast()
    {
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

        define('LASTEST_VERSION', $lastVersionData ?? ZN_VERSION);

        return $return;
    }
}