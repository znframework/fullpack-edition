<?php
class InternalUserActivities extends GrandModel
{
    const table = 'user_activities';    

    public function joinUsers()
    {
        return $this->leftJoin('users.id', 'user_activities.user_id')
                    ->limit(Post::start(), 20)
                    ->orderBy('user_activities.id', 'desc')
                    ->select
                    (
                        'users.name as name, users.photo as photo', 
                        'user_activities.activity as activity, user_activities.date as date'
                    );
    }
}