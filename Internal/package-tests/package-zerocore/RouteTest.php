<?php namespace ZN;

use Post;
use Route;

class RouteTest extends ZerocoreExtends
{
    public function testChange()
    {
        $this->assertNull(Route::change('product-add')->uri('product/add'));
    }

    public function testDB()
    {
        $this->assertNull(Route::change('[blog:slug]')->uri('blog'));
        $this->assertNull(Route::change('profile/[account:name]')->uri('account/profile'));
    }

    public function testUsable()
    {
        $this->assertNull(Route::usable()->change('product-add')->uri('product/add'));
    }

    public function testCSRF()
    {
        $this->assertNull(Route::csrf()->method('post')->uri('product/add'));
    }

    public function testMethod()
    {
        $this->assertNull(Route::method('post', 'get', 'put', 'delete')->uri('product/add'));
    }

    public function testRestore()
    {
        $this->assertNull(Route::restore(['127.0.0.1', '127.0.0.2'], 'Home/restore')->uri('product/add'));
        $this->assertNull(Route::restore('127.0.0.1', 'Home/restore')->uri('product/add'));
    }

    public function testAjax()
    {
        $this->assertNull(Route::ajax()->redirect('Home/invalidRequest')->uri('product/ajaxAddItem'));
    }

    public function testRedirect()
    {
        $this->assertNull(Route::ajax()->redirect('Home/invalidRequest')->uri('product/ajaxAddItem'));
    }

    public function testShow404()
    {
        $this->assertNull(Route::change('404')->show404('Home/s404'));
        $this->assertNull(Route::direct()->show404('Home/s404'));
    }

    public function testContainer()
    {
        $this->assertNull(Route::method('post')->csrf()->container(function()
        {
            Route::uri('product/add');
            Route::change('product-list')->uri('product/list');
        }));

        $this->assertNull(Route::redirect('404')->container(function()
        {
            Route::method('get')->container(function()
            {
                Route::ajax()->container(function()
                {
                    Route::uri('product/edit');
                });
            });
        
            Route::method('post')->container(function()
            {
                Route::csrf()->container(function()
                {
                    Route::uri('product/add');
                    Route::uri('store/add');
                });
            });
        }));
    }

    public function testCallback()
    {
        $this->assertNull(Route::change('user-{word}')->callback(function()
        {
            Post::send('Example Data');
        
        })->uri('user/profile'));
    }
}