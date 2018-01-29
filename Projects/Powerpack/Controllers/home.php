<?php namespace Project\Controllers;

use Users, Date, UserActivities, Cache, JC, Post, Lang;

class Home extends Controller
{
    public function main(String $params = NULL)
    {
        $activities = UserActivities::joinUsers();

        View::userCount(Users::totalRows())
            ->current(Date::standart())
            ->activities($activities->result())
            ->pagination(JC::pagination($activities, function($paginate)
            {
                $paginate->type('ajax');
            }));
    }

    public function setlang(String $lang)
    {
        Lang::set($lang);

        redirect(prevUrl());
    }
}
