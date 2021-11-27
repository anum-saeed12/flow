<?php

use App\Models\User;
use App\Models\TaskUser;
use App\Models\ProjectUser;

if (!function_exists('members'))
{
    function members($id, $type='task')
    {
        $model = $type=='task'?'TaskUser':'ProjectUser';

        $table = $type=='task'?(new TaskUser())->getTable():(new ProjectUser())->getTable();
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
            ProjectUser::select($select)
                ->join($users, "{$users}.id", "=", "{$table}.user_id")
                ->where("{$table}.project_id", $id)
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

if (!function_exists('tasks'))
{
    function tasks($project_id,$user_id=null)
    {

        $select = [
            'tasks.title',
            'tasks.id',
            'tasks.description',
            'tasks.start_date',
            'tasks.end_date',
            'tasks.points',
            'tasks.completed',
        ];

        $tasks = TaskUser::select($select)
            ->join('tasks','tasks.id','=','task_users.task_id')
            ->where('tasks.project_id',$project_id);

        if(!is_null($user_id)) $tasks= $tasks->where('task_users.user_id',$user_id);
        return $tasks->groupBy('tasks.project_id')->get();
    }
}
