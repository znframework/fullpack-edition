<?php namespace ZN\Remote;

class RemoteExtendz extends \ZN\Test\GlobalExtends
{
    protected $ftp;

    public function __construct()
    {
        parent::__construct();

        $this->ftp = $this->createMock(FTP::class);
    }
    
}