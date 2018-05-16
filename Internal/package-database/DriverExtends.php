<?php namespace ZN\Database;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Lang;
use ZN\Singleton;

class DriverExtends
{
    protected $differentConnection;
    protected $settings;
    protected $getLang;

    public function __construct($settings = [])
    {
        $this->settings = $settings;
        $this->differentConnection = Singleton::class('ZN\Database\DB')->differentConnection($settings);
        $this->getLang = Lang::default('ZN\Database\DatabaseDefaultLanguage')::select('Database');
    }
}
