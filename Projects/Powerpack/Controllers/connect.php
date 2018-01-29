<?php namespace Project\Controllers;

use Json, File, Config, Arrays, ML, Lang;

class Connect extends Controller
{
    public function main()
    {
        if( ZN_VERSION < ($version = Config::dashboard('version')) )
        {
            trace(lang('PowerpackErrors', 'versionError', ['%' => $version, '#' => ZN_VERSION]));
        }

        if( is_file($connect = CONNECT_FILE) )
        {
            Config::set('Database', 'database', Json::decode(File::read($connect)));
        }

        $all = ML::selectAll();

        View::languages(Arrays::keys($all))->dict((object) $all[Lang::get()]);
    }
}
