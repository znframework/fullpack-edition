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
    public function __construct(callable $callable, int $decrement, Job $crontab)
    {
        $backtrace = (object) debug_backtrace(2)[4];

        $id = $crontab->getJobIdFromExecFileWithTerm($backtrace->class, $backtrace->function);

        if( $id !== false )
        {
            $this->start($id, 0, $key, $getJsonData, $limit, $crontab);

            if( $callable($limit, $decrement) === false )
            {
                $this->removeKeyFromJsonData($key, $getJsonData);
            }
            else
            {
                $limit += $decrement;

                $getJsonData[$key] = $limit;
            }
               
            $this->end($getJsonData);
        }
    } 
}
