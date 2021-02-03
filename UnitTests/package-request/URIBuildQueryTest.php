<?php namespace ZN\Request;

use URI;

class URIBuildQueryTest extends \PHPUnit\Framework\TestCase
{
    public function testBuildQuery()
    {
        $query = URI::buildQuery(['foo' => 'FOO', 'bar' => 'BAR']);

        $this->assertEquals('foo/FOO/bar/BAR', $query);
    }

    public function testBuildQueryNumericKey()
    {
        $query = URI::buildQuery(['foo' => 'FOO', 'bar']);

        $this->assertEquals('foo/FOO/bar', $query);
    }

    public function testBuildQueryLeft()
    {
        $query = URI::buildQuery(['foo' => 'FOO', 'bar' => 'BAR'], '/', 'left');

        $this->assertEquals('/foo/FOO/bar/BAR', $query);
    }

    public function testBuildQueryRight()
    {
        $query = URI::buildQuery(['foo' => 'FOO', 'bar' => 'BAR'], '/', 'right');

        $this->assertEquals('foo/FOO/bar/BAR/', $query);
    }

    public function testBuildQueryBoth()
    {
        $query = URI::buildQuery(['foo' => 'FOO', 'bar' => 'BAR'], '/', 'both');

        $this->assertEquals('/foo/FOO/bar/BAR/', $query);
    }
}