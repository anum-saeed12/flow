<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listicle;
use App\Models\ListUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *s
     */
    public function index()
    {
        $listings = Listicle::with('tasks')->get();
        $users = User::all();
        $data = [
            'title'  => 'Tasks',
            'listings' => $listings,
            'users' => $users
        ];
        return view('lists.listings', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        # Expected parameters
        # 1. List Title
        # 2. Description
        # 3. Members (array)

        $request->validate([
            'title'       => 'required|max:150',
            'description' => 'required|max:300'
        ]);

        $new_list = new Listicle();
        $new_list->title = $request->input('title');
        $new_list->description = $request->input('description');
        $new_list->created_by = Auth::id();
        $new_list->save();

        # If members are provided, add members to the newly created list
        if ($request->input('members')) {
            foreach($request->input('members') as $member) {
                $new_member = new ListUser();
                $new_member->user_id = $member;
                $new_member->list_id = $new_list->id;
                $new_member->save();
            }
        }

        return redirect()->back()->with('success', 'List has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:150',
            'description' => 'required|max:300'
        ]);

        $updated_list = Listicle::find($id);
        $updated_list->title = $request->input('title');
        $updated_list->description = $request->input('description');
        $updated_list->updated_by = Auth::id();
        $updated_list->save();

        # Remove previous records for members
        $old_members = ListUser::where('list_id', $id)->delete();

        # If members are provided, add members to the newly created list
        if ($request->input('members')) {
            foreach($request->input('members') as $member) {
                $new_member = new ListUser();
                $new_member->user_id = $member;
                $new_member->list_id = $id;
                $new_member->save();
            }
        }

        return redirect()->back()->with('success', 'List has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $list = Listicle::find($id);
        # Remove members
        $remove_members = ListUser::where('list_id', $id)->delete();
        $list->delete();

        return redirect()->back()->with('success', 'List has been deleted');
    }
}
