<?php namespace ZN;

class WizardTest extends ZerocoreExtends
{
    public function testHtml()
    {
        Config::viewObjects('wizard', ['html' => true]);
        $this->assertEquals('<b>1</b>', Wizard::data('#b 1 ##b'));
    }
}