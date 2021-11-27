<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();


        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user
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
        return view("admin.dashboard", $data);
    }

}
