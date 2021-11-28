<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *s
     */
    public function index()
    {
        $project_user = (new ProjectUser())->getTable();
        $project = (new Project())->getTable();
        $select = [
            "{$project}.id",
            "{$project}.title",
            "{$project}.description",
            "{$project}.sorting_order",
            "{$project}.created_by",
            "{$project}.updated_by",
        ];
        $projects = ProjectUser::select($select)
            ->join($project, "{$project}.id", "=", "{$project_user}.project_id")
            ->where("{$project_user}.user_id", Auth::id())
            ->get();

        $users = User::all();
        $data = [
            'title'  => 'Project & Tasks',
            'projects' => $projects,
            'users' => $users
        ];
        return view('projects.listings', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        //
    }
}
