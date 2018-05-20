<?php namespace ZN\Tests;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Controller\UnitTest;

class Filesystem extends UnitTest
{
    public function _zipExtract()
    {
        $this->compare(true, $this->zipExtract($path = EXTERNAL_FILES_DIR . 'DefaultProject'), $path);
    }

    public function _copy()
    {
        $this->compare(true, $this->copy($path = EXTERNAL_FILES_DIR . 'DefaultProject', $path . '2'));
    }

    public function _createFile()
    {
        if( file_exists(EXTERNAL_FILES_DIR . 'Example.txt') )
        {
            $return = false;
        }
        else
        {
            $return = true;
        }

        $this->compare($return, $this->createFile(EXTERNAL_FILES_DIR . 'Example.txt'));
    }

    public function _replaceData()
    {
        $this->compare('', $this->replaceData(EXTERNAL_FILES_DIR . 'DefaultProject2/Butchery/empty', '', 'New Data'));
    }

    public function _deleteFolder()
    {
        $this->compare(true, $this->deleteFolder(EXTERNAL_FILES_DIR . 'DefaultProject2'));
    }

    public function _createFolder()
    {
        $this->compare(true, $this->createFolder(EXTERNAL_FILES_DIR . 'Example'));
    }

    public function _deleteEmptyFolder()
    {
        $this->compare(true, $this->deleteEmptyFolder(EXTERNAL_FILES_DIR . 'Example'));
    }

    public function _getExtension()
    {
        $this->compare('txt', $this->getExtension(EXTERNAL_FILES_DIR . 'Example.txt'));
    }

    public function _getFiles()
    {
        $this->compare($return = $this->getFiles(EXTERNAL_FILES_DIR), $return);
    }

    public function _getFiles2()
    {
        $this->compare($return = $this->getFiles(EXTERNAL_FILES_DIR, 'dir'), $return);
    }

    public function _getFiles3()
    {
        $this->compare($return = $this->getFiles(EXTERNAL_FILES_DIR, ['dir', 'php']), $return);
    }

    public function _getRecursiveFiles()
    {
        $this->compare($return = $this->getFiles(EXTERNAL_FILES_DIR), $return);
    }

    public function _getRecursiveFiles2()
    {
        $this->compare($return = $this->getFiles(EXTERNAL_FILES_DIR, true, true), $return);
    }
}
