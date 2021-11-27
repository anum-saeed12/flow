<?php

use App\Models\User;
use App\Models\TaskUser;
use App\Models\ListUser;

if (!function_exists('members'))
{
    function members($id, $type='task')
    {
        $model = $type=='task'?'TaskUser':'ListUser';

        $table = $type=='task'?(new TaskUser())->getTable():(new ListUser())->getTable();
        $users = (new User())->getTable();

        $select = [
            "{$users}.id",
            "{$users}.username",
            "{$users}.user_role",
        ];

        #$model == 'task' && $select[] = "{$table}.points";

        $members = $type=='task' ?
            TaskUser::select($select)
            ->join($users, "{$users}.id", "=", "{$table}.user_id")
            ->where("{$table}.task_id", $id)
            ->get()
            :
            ListUser::select($select)
                ->join($users, "{$users}.id", "=", "{$table}.user_id")
                ->where("{$table}.list_id", $id)
                ->get();

        $user_ids = [];
        foreach($members as $member) $user_ids[] = $member->id;

        return $user_ids;
    }
}

if (!function_exists('crop'))
{
    function crop($string, $limit=40)
    {
        if (strlen($string) <= $limit) return $string;
        return substr($string, 0, $limit) . "...";
    }
}
