<?php namespace ZN\Hypertext;


class HypertextExtends extends \ZN\Test\GlobalExtends
{
    public function __construct()
    {
        parent::__construct();

        $this->mock = new Class extends Form
        {
            public function mockSetSelectedAttribute(&$selected)
            {
                $this->settings['selectedValue'] = 'a';

                return $this->setSelectedAttribute($selected, ['a' => 'a']);
            }
        };
    }
}