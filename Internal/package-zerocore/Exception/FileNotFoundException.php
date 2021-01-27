<?php namespace ZN\Exception;
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
use Exception;

class FileNotFoundException extends Exception
{
    public function __construct($file)
    {
        parent::__construct(Lang::default('ZN\CoreDefaultLanguage')::select('Exception', 'fileNotFound', $file));
    }
}
