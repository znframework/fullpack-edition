<?php namespace Home;

use Folder;
use File;
use Cookie;
use ZN\Model;

class Themes extends Model
{
    public function selectEditor($theme)
    {
        Cookie::insert('editorTheme', $theme);
    }

    public static function extract()
    {
        $themesZip = Folder::files(EXTERNAL_BUTCHERY_DIR, 'zip');

        if( ! empty($themesZip) ) foreach( $themesZip as $zip )
        {
            $target = EXTERNAL_BUTCHERY_DIR . rtrim($zip, '.zip');

            if( ! file_exists($target) || ! Folder::files($target) )
            {
                File::zipExtract(EXTERNAL_BUTCHERY_DIR . $zip, $target);
            }
        }
    }

    public static function get()
    {
        $butcheryFiles  = Folder::files(EXTERNAL_BUTCHERY_DIR, 'dir');
        $butcheryThemes = [];

        foreach( $butcheryFiles as $bf )
        {
            if( Folder::files(EXTERNAL_BUTCHERY_DIR . $bf, 'dir') )
            {
                $butcheryThemes[] = $bf;
            }
        }

        return $butcheryThemes;
    }
}