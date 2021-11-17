<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'name'       => 'required',
            'password'   => 'required',
            'user_role'  => 'required|in:admin,sale,manager,team'
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

        if($request->user_role == 'team')
        {
            # Verify if categories exist
            $verification = Category::select('id')->whereIn('id', $request->category_id)->get();
            if (count($request->category_id) != $verification->count()) return redirect()->back()->with('error', 'Please select valid categories');

            $data             =  $request->all();
            $user             =  new User($data);
            $user['password'] =  Hash::make($request->password);
            $user->save();

            foreach ($request->category_id as $category) {
                $user_category = new UserCategory();
                $user_category->category_id = $category;
                $user_category->user_id = $user->id;
                $user_category->save();
            }
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
        $user = User::find($id);

        $request->validate([
            'email'      => 'sometimes|required',
            'username'   => 'sometimes|required',
            #'password'   => 'sometimes|required',
            'user_role'  => 'sometimes|required|in:admin,sale,manager,team'
        ]);

        $request->input('email')       &&  $user->email        = $request->input('email');
        empty($request->passowrd)      ||  $user->password     = Hash::make($request->input('password'));
        $request->input('username')    &&  $user->username     = $request->input('username');
        $request->input('user_role')   &&  $user->user_role    = $request->input('user_role');
        $user->save();

        if($request->user_role == 'team')
        {
            # Verify if categories exist
            $verification = Category::select('id')->whereIn('id', $request->category_id)->get();
            if (count($request->category_id) != $verification->count()) return redirect()->back()->with('error', 'Please select valid categories');

            $delete_old_categories = UserCategory::where('user_id', $user->id)->delete();

            foreach ($request->category_id as $category) {
                $user_category = new UserCategory();
                $user_category->category_id = $category;
                $user_category->user_id = $user->id;
                $user_category->save();
            }
        }

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
