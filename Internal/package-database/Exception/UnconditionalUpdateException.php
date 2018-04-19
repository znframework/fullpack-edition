<?php namespace ZN\Database\Exception;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Exception;

class UnconditionalUpdateException extends Exception
{
    const lang = 
    [
        'placement' => 
        [
            '#' => '[DB::where(mixed $column, string $value [, string $condition = "and"])]'
        ],
        'tr' => 'Koşulsuz güncelleme işlemi gerçekleştiremezsiniz! Lütfen # ile koşul tanımlayın.',
        'en' => 'You can not perform unconditional update! Please define the condition with #.'
    ];
}
