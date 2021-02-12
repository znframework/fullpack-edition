<?php namespace ZN;

use File;
use Folder;
use Butcher;
use Generate;

class ButcherTest extends ZerocoreExtends
{
    public function testRun()
    {
        Folder::copy(self::resources . 'theme.zip', BUTCHERY_DIR . 'theme.zip');
        Butcher::runDelete('Example');
        Butcher::runDelete('Example', 'external');
    }

    public function testRunMultiple()
    {
        Folder::create(BUTCHERY_DIR);
        Folder::copy(self::resources . 'theme.zip', BUTCHERY_DIR . 'theme1.zip');
        Folder::copy(self::resources . 'theme.zip', BUTCHERY_DIR . 'theme2.zip');
        
        File::zipExtract(BUTCHERY_DIR . 'theme1.zip');
        File::zipExtract(BUTCHERY_DIR . 'theme2.zip');

        Butcher::runDelete('multiple');
    }

    public function testExtract()
    {
        Folder::copy(self::resources . 'theme.zip', EXTERNAL_BUTCHERY_DIR . 'theme.zip');
        Butcher::extractDelete('all');
        Folder::delete(PROJECTS_DIR . 'Theme');
    }

    public function testExtractForceSlug()
    {
        Folder::copy(self::resources . 'theme.zip', EXTERNAL_BUTCHERY_DIR . 'theme.zip');
        Butcher::extractForce('theme', 'slug');
        Folder::delete(PROJECTS_DIR . 'theme');
    }

    public function testExtractForceLower()
    {
        Folder::copy(self::resources . 'theme.zip', EXTERNAL_BUTCHERY_DIR . 'theme.zip');
        Butcher::extractForce('theme', 'lower');
        Folder::delete(PROJECTS_DIR . 'theme');
    }

    public function testExtractForceOrder()
    {
        Folder::copy(self::resources . 'theme.zip', EXTERNAL_BUTCHERY_DIR . 'theme.zip');
        Butcher::extractForce('all', 'theme');
        Folder::delete(PROJECTS_DIR . 'theme');
    }

    public function testExtractForceOrderStart()
    {
        Folder::copy(self::resources . 'theme.zip', EXTERNAL_BUTCHERY_DIR . 'theme.zip');
        Butcher::extractForce('all', 'theme:inc[1]');
        Folder::delete(PROJECTS_DIR . 'theme1');
    }

    public function testExtractForceOrderRand()
    {
        Folder::copy(self::resources . 'theme.zip', EXTERNAL_BUTCHERY_DIR . 'theme.zip');
        Butcher::extractForce('all', 'theme:rand[1,1]');
        Folder::delete(PROJECTS_DIR . 'theme1');
    }

    public function testApplication()
    {
        $this->assertIsObject(Butcher::application('Frontend'));
    }

    public function testCleanComments()
    {
        $this->assertIsObject(Butcher::cleanComments());
    }

    public function testLocationException()
    {
        try
        {
            Butcher::location('unknownlocation');
        }
        catch( \Exception $e )
        {
            $this->assertStringContainsString('[project]', $e->getMessage());
        }

        $this->assertIsObject(Butcher::location('project'));
    }

    public function testDefaultProjectFileException()
    {
        try
        {
            Butcher::defaultProjectFile('unknownfile');
        }
        catch( \Exception $e )
        {
            $this->assertEquals('`unknownfile.zip` file was not found!', $e->getMessage());
        }
    } 
}