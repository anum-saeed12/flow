<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title' => 'Dashboard',
            'user' => $user
        ];
        $role = Auth::user()->user_role;
        return view("dashboard", $data);
    }

    public function admin()
    {
        $user = (new User())->getTable();
        $task_user = (new TaskUser())->getTable();

        $select = [
            "{$user}.id",
            "{$user}.username",
            "{$user}.user_role",
            DB::raw("SUM({$task_user}.points) as points"),
        ];
        $users = User::select($select)
            ->join($task_user,"{$task_user}.user_id","=","{$user}.id")
            ->groupBy("{$user}.id")
            ->get();

        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => Auth::user(),
            'users'         => $users
        ];
        return view("admin.dashboard", $data);
    }
    public function employee()
    {
        $user = Auth::user();

        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user
        ];
        return view("employee.dashboard", $data);
    }

}
