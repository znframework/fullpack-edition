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

use Folder;
use Validation;
use Method;
use URI;
use File;
use Arrays;
use Redirect;
use Masterpage;
use ZN\Base;
use ZN\Model;

class Config extends Model
{
    public static function sendMasterpageData()
    {
        $pdata['content']    = 'config';
        $pdata['deletePath'] = 'Config';

        $files = Folder::allFiles($pdata['fullPath'] = SELECT_PROJECT_DIR . 'Config', true);

        if( defined('SETTINGS_DIR') )
        {
            $settings = Folder::allFiles(SETTINGS_DIR);
        }
        else
        {
            $settings =  'Projects' . DS . 'Projects.php';
        }

        $pdata['files'] = Arrays::addFirst($files, $settings);

        Masterpage::pdata($pdata);
    }

    public static function run()
    {
        Validation::rules('config', ['required', 'alnum'], LANG['configName']);

        if( ! $error = Validation::error('string') )
        {
            $functions = explode(',', Method::post('functions'));

            $configContent = '<?php return' . EOL . '[' . EOL . HT . '\'key\' => \'value\'' . EOL . '];';

            $configPath = SELECT_PROJECT_DIR . 'Config' . Base::suffix(Base::prefix(Method::post('config')), '.php');

            if( ! File::exists($configPath) )
            {
                File::write($configPath, $configContent);
            }

            Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}