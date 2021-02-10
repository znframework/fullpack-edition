<?php namespace ZN\Authentication;

class UserExtendsTest extends AuthenticationExtends
{
    public function testColumn()
    {
        (new UserExtends)->column('address', 'paris');

        $this->assertEquals('paris', Properties::$parameters['column']['address']);
    }

    public function testReturnLink()
    {
        (new UserExtends)->returnLink('return/link');

        $this->assertEquals('return/link', Properties::$parameters['returnLink']);
    }

    public function testSetEmailTemplate()
    {
        (new UserExtends)->setEmailTemplate('message {user}, {pass}, {url}');

        $this->assertEquals('message {user}, {pass}, {url}', Properties::$setEmailTemplate);
    }

    public function testGetEmailTemplate()
    {
        $this->assertEquals('message userx, passx, [urlx]urlx', $this->userExtendsMock->mockGetEmailTemplate());
    }

    public function testAutoMatchColumns()
    {
        $this->userExtendsMock->mockAutoMatchColumns('post');
    }

    public function testGetUserTableColumns()
    {
        $this->assertIsArray($this->userExtendsMock->mockGetUserTableColumns());
    }
}