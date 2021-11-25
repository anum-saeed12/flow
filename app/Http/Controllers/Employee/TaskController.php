<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        # Expected Parameters
        # 1. Title (List Title)_
        # 2. Description
        # 3. Points
        # 4. Start Date (Optional)
        # 5. End Date (Optional)

        $request->validate([
            'title' => 'required|max:150',
            'description' => 'required|max:300',
            'points' => 'required|numeric',
            'list_id' => 'required|exists:App\Models\Listicle,id'
        ]);

        $new_task = new Task();
        $new_task->title = $request->input('title');
        $new_task->description = $request->input('description');
        $new_task->points = $request->input('points');
        $new_task->list_id = $request->input('list_id');
        $request->start_date && $new_task->start_date = $request->input('start_date');
        $request->end_date && $new_task->end_date = $request->input('end_date');
        $new_task->created_by = Auth::id();


        $new_task->save();

        return redirect()->back()->with('success', 'Task has been added');
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
