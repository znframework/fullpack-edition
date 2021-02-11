<?php namespace ZN;

class ZerocoreExtends extends \ZN\Test\GlobalExtends
{
    const resources = self::default . 'package-zerocore/resources/';
    
    public function __construct()
    {
        parent::__construct();

        $this->autoloaderMock = new class extends Autoloader
        {
            public function mockCreateClassMapTopOutput(&$output)
            {
                self::$path = 'unknown';

                $this->createClassMapTopOutput($output);
            }

            public function mockAliases()
            {
                $this->aliases();
            }

            public function mockGetFacadeContent()
            {
                return $this->getFacadeContent('Facade', 'Target\Class', 'const example = 1;');
            }

            public function mockGetClassNamespace(&$facade, &$namespace)
            {
                return $this->getClassNamespace($facade, $namespace);
            }
        };
    }
}