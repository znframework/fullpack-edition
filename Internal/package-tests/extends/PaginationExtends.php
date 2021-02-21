<?php namespace ZN\Pagination;


class PaginationExtends extends \ZN\Test\GlobalExtends
{
    public function __construct()
    {
        parent::__construct();

        $this->mock = new Class extends Paginator
        {
            public function mockExplodeRequestGetValue()
            {
                $_SERVER['REQUEST_URI'] = 'foo/bar?baz=baz';

                return $this->explodeRequestGetValue();
            }

            public function mockCreateBasicPaginationBar()
            {
                return $this->createBasicPaginationBar(100);
            }
        };
    }
}