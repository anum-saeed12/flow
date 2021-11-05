<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class   DashboardController extends Controller
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
        # Fetch User
        $total_user = User::select(DB::raw('COUNT(*) as total'))->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_user'    => $total_user,
            'currency'      => 'PKR',
        ];
        return view("admin.dashboard", $data);
    }
    public function manager()
    {
        $user = Auth::user();
        # Fetch User
        $total_user = User::select(DB::raw('COUNT(*) as total'))->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_user'    => $total_user,
        ];
        return view("manager.dashboard", $data);
    }
    public function sale()
    {
        $user = Auth::user();
        # Fetch User
        $total_user = User::select(DB::raw('COUNT(*) as total'))->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_user'    => $total_user,
        ];
        return view("sale.dashboard", $data);
    }
    public function team()
    {
        $user = Auth::user();
        # Fetch User
        $total_user = User::select(DB::raw('COUNT(*) as total'))->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_user'    => $total_user,
        ];
        return view("team.dashboard", $data);
    }
}
