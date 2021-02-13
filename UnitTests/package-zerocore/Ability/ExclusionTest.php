<?php namespace ZN;

use File;

class ExclusionTest extends ZerocoreExtends
{
    public function testObject()
    {
        try
        {
            File::reglace('unknownfile', 'xyaz', 'abc');
        }
        catch( \Exception $e )
        {
            try
            {
                throw new Exception($e);
            }
            catch( Exception $e )
            {
                //$e->continue();
            }
        }
    }
}