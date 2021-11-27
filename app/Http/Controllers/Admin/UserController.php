<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::orderBy('id','DESC')->paginate($this->count);
        $data = [
            'title'  => 'Users',
            'user'   => Auth::user(),
            'users'  => $user
        ];
        return view('admin.user.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add User',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user()
        ];
        return view('admin.user.add', $data);
    }

    public function edit($id)
    {
        $select=[
            'users.id',
            'users.username',
            'users.email',
            'users.user_role',
        ];
        $user = User::select($select)
            ->where('id', $id)
            ->first();

        $data = [
            'title'    => 'Update User',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'users'    => $user
        ];
        return view('admin.user.edit', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'email'      => 'required',
            'username'   => 'required',
            'password'   => 'required',
            'user_role'  => 'required|in:admin,employee'
        ];

        $request->validate($rules);

        $exist = User::where('username',$request->username)
            ->where('email',$request->email)->first();

        if($exist)
        {
            return redirect(
                route('user.list.admin')
            )->with('success', 'User already exists!!');
        }

        $data             =  $request->all();
        $user             =  new User($data);
        $user['password'] =  Hash::make($request->password);
        $user->save();

        return redirect(
            route('user.list.admin')
        )->with('success', 'User was added successfully!');
    }

    public function update (Request $request,$id)
    {
        $rules = [
            'email'      => 'required',
            'username'   => 'required',
            'password'   => 'required',
            'user_role'  => 'required|in:admin,employee'
        ];

        $request->validate($rules);

        $user = User::find($id);

        $request->input('email')     && $user->email     = $request->input('email');
        empty($request->passowrd)         || $user->password  = Hash::make($request->input('password'));
        $request->input('username')  && $user->username  = $request->input('username');
        $request->input('user_role') && $user->user_role = $request->input('user_role');
        $user->save();


        return redirect(
            route('user.list.admin')
        )->with('success', 'User updated successfully!');
    }

    public function delete($id)
    {
        $user = User::find($id)->delete();
        return redirect(
            route('user.list.admin')
        )->with('success', 'User deleted successfully!');
    }
}
