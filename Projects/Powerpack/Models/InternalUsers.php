<?php
class InternalUsers extends GrandModel
{
    const table = 'users';
    
    public function activity(String $activity)
    {
        $user = User::data();

        UserActivities::user_id($user->id)
                      ->activity($activity)
                      ->date(Date::set('{year}-{monthNumber}-{dayNumber} {hour}-{minute}-{second}'))
                      ->insert();
    }
}