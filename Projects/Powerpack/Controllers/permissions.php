<?php namespace Project\Controllers;

use DBGrid;

class Permissions extends Controller
{
    public function main()
    {
        View::pageTitle('Permissions')->pageBody
        (
            DBGrid::columns('id as RoleID', 'type as Type', 'rules as Rules')
                  ->inputs
                  ([
                        'type' => function($form, $name, $value)
                        { 
                                return $form->select($name, ['noperm' => 'noperm', 'perm' => 'perm'], $value);
                        }
                  ])
                  ->create('permissions')
        );
    }
}
