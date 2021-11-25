<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Listicle;
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
        $listings = [
            (Object) ['id' => 1, 'title' => 'Backlog', 'description' => 'Backlog description', 'tasks' => []],
            (Object) ['id' => 2, 'title' => 'In Progress', 'description' => '', 'tasks' => []],
            (Object) ['id' => 3, 'title' => 'Completed', 'description' => 'Yo bro', 'tasks' => []],
            (Object) ['id' => 4, 'title' => 'Bugs & Issues', 'description' => '', 'tasks' => []],
            (Object) ['id' => 5, 'title' => 'Testing', 'description' => '', 'tasks' => []],
        ];
        $listings = Listicle::with('tasks')->get();
        $data = [
            'title'  => 'Tasks',
            'listings' => $listings
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
        $list->delete();

        return redirect()->back()->with('success', 'List has been deleted');
    }
}
