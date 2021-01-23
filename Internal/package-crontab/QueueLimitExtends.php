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
class QueueLimitExtends
{
    /**
     * Keeps Crontab object
     * 
     * @var Crontab
     */
    protected $crontab;

    /**
     * Protected start
     */
    protected function start($id, $start, &$key, &$getJsonData, &$limit, $crontab)
    {
        $this->crontab = $crontab;

        $this->setFile();
        
        $key = $this->getProcessId($id);

        if( ! is_file($this->file) )
        {
            $this->addDataToFile([$key => $start]);
        }
        
        $getJsonData = $this->getJsonDataToFile();

        $limit = (int) ($getJsonData[$key] ?? 1);
    }

    /**
     * Protected end
     */
    protected function end($getJsonData)
    {
        $this->addDataToFile($getJsonData);
    }

    /**
     * Protected remove key from json data
     */
    protected function removeKeyFromJsonData($key, &$getJsonData)
    {
        $this->crontab->remove((int) ltrim($key, 'ID'));

        if( isset($getJsonData[$key]) )
        {
            unset($getJsonData[$key]);
        }
    }

    /**
     * Protected get json data to limit/queue file
     */
    protected function getJsonDataToFile()
    {
        return json_decode(file_get_contents($this->file), true);
    }

    /**
     * Protected set queue file
     */
    protected function setFile()
    {
        $this->file = $this->crontab->getCrontabCommands() . $this->file;
    } 

    /**
     * Protected function get process id
     */
    protected function getProcessId($id)
    {
        return 'ID' . $id;
    }

    /**
     * Protected add data to limit/queue file
     */
    protected function addDataToFile($data)
    {
        file_put_contents($this->file, json_encode($data) . EOL);
    }
}
