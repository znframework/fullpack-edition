<?php namespace Generate;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use URI;
use URL;
use File as ZNFile;
use Redirect;
use Method;
use Security;
use ZN\Model;

class File extends Model
{
    public static function delete()
    {
        $file = URI::get('deleteFile', 'all');

        if( ZNFile::exists($file) )
        {
            ZNFile::delete($file);
        }

        Redirect::location((string) URL::prev(), 0, ['success' => LANG['success']]);
    }

    public static function rename()
    {
        $old = Method::post('old');
        $new = Method::post('new');

        $controlOld = ZNFile::pathInfo($old, 'dirname');
        $controlNew = ZNFile::pathInfo($new, 'dirname');

        if( $controlOld === $controlNew )
        {
            ZNFile::rename($old, $new);
        }
    }

    public static function save()
    {
        $link    = Method::post('link');
        $content = Method::post('content');

        ZNFile::write($link, Security::htmlDecode($content));
    }
}