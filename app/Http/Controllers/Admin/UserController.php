<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\UserCategory;
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
        $category = Category::all();
        $data = [
            'title'    => 'Add User',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'category' => $category
        ];
        return view('admin.user.add', $data);
    }

    public function edit($id)
    {
        $user = User::find($id)->first();
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
        $request->validate([
            'email'      => 'required',
            'username'   => 'required',
            'name'       => 'required',
            'password'   => 'required',
            'category_id'=> 'nullable',
            'user_role'  => 'required|in:admin,sale,manager,team'
        ]);

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
        $user['password'] = Hash::make($request->password);
        $user->save();

        if($request->category_id)
        {
            $data            =  $request->all('category_id');
            $user_category   =  new UserCategory($data);
            $user_category['user_id'] = $user->id;
            $user_category->save();
        }

        return redirect(
            route('user.list.admin')
        )->with('success', 'User was added successfully!');
    }

    public function update (Request $request,$id)
    {
        $user = User::find($id);

        $request->validate([
            'email'      => 'sometimes|required',
            'username'   => 'sometimes|required',
            'password'   => 'sometimes|required',
            'user_role'  => 'sometimes|required|in:admin,sales_person,manager,sourcing_team'
        ]);

        $request->input('email')       &&  $user->email        = $request->input('email');
        $request->input('password')    &&  $user->password     = $request->input('password');
        $request->input('username')    &&  $user->username     = $request->input('username');
        $request->input('user_role')   &&  $user->user_role    = $request->input('user_role');
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
