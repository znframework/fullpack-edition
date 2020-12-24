<?php namespace ZN\Crontab;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */
class Queue extends QueueLimitExtends
{
    /**
     * Keeps queue file name.
     * 
     * @var string
     */
    protected $file = 'Queue.json';

    /**
     * Magic constructor
     * 
     * @param string $path
     */
    public function __construct(Int $id, Callable $callable, Int $decrement, Job $crontab)
    {
        $this->start($id, 0, $key, $getJsonData, $limit, $crontab);

        if( $callable($limit, $decrement) === false )
        {
            $this->removeKeyFromJsonData($key, $getJsonData);
        }
        else
        {
            $getJsonData[$key] = $limit += $decrement;
        }
           
        $this->end($getJsonData);
    } 
}
