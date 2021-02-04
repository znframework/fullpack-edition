<?php namespace ZN\Language;

use ML;
use Post;

class GridTest extends \PHPUnit\Framework\TestCase
{
    public function testUrl()
    {
        $this->assertStringContainsString('ML_TABLE', ML::url('Home/main')->grid());
    }

    public function testLimit()
    {
        $this->assertStringContainsString('ML_TABLE', ML::limit(4)->grid());
    }

    public function testSearch()
    {
        unset($_POST);

        Post::ML_SEARCH_SUBMIT('abc');

        $this->assertStringContainsString('ML_TABLE', ML::grid());
    }

    public function testAddLanguage()
    {
        unset($_POST);

        Post::ML_ADD_ALL_LANGUAGE_SUBMIT(true);
        Post::ML_ADD_LANGUAGE('en');

        $this->assertStringContainsString('ML_TABLE', ML::grid());
    }

    public function testAllDelete()
    {
        unset($_POST);

        Post::ML_ALL_DELETE_SUBMIT(true);
        Post::ML_ALL_DELETE_HIDDEN(NULL);

        $this->assertStringContainsString('ML_TABLE', ML::grid());
    }

    public function testAddKeyword()
    {
        unset($_POST);

        Post::ML_LANGUAGES('en,tr');

        Post::ML_ADD_KEYWORD_SUBMIT(true);
        Post::ML_ADD_KEYWORD(1);
        Post::ML_ADD_WORDS(['en' => 'Example', 'tr' => 'Deneme']);

        $this->assertStringContainsString('ML_TABLE', ML::grid());
    }

    public function testAddKeywordNumeric()
    {
        unset($_POST);

        Post::ML_LANGUAGES('en,tr');

        Post::ML_ADD_KEYWORD_SUBMIT(true);
        Post::ML_ADD_KEYWORD('example');
        Post::ML_ADD_WORDS(['en' => 'Example', 'tr' => 'Deneme']);

        $this->assertStringContainsString('ML_TABLE', ML::grid());
    }

    public function testUpdateKeyword()
    {
        unset($_POST);

        Post::ML_LANGUAGES('en,tr');
        Post::ML_UPDATE_KEYWORD_HIDDEN('example');
        Post::ML_UPDATE_WORDS(['en' => 'Example2', 'tr' => 'Deneme2']);

        $this->assertStringContainsString('ML_TABLE', ML::grid());
    }

    public function testDeleteKeyword()
    {
        unset($_POST);

        Post::ML_LANGUAGES('en,tr');
        Post::ML_UPDATE_KEYWORD_HIDDEN('example');
        Post::ML_DELETE_SUBMIT(true);
        
        $this->assertStringContainsString('ML_TABLE', ML::grid());
    }
}