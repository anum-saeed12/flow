<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Carbon\Carbon;
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
        $projects = Project::with('tasks')->get();
        $users = User::all();
        $data = [
            'title'  => 'Projects & Tasks',
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
        # Expected parameters
        # 1. Project Title
        # 2. Description
        # 3. Members (array)

        $request->validate([
            'title'       => 'required|max:150',
            'description' => 'required|max:300'
        ]);

        $new_project = new Project();
        $new_project->title = $request->input('title');
        $new_project->description = $request->input('description');
        $new_project->created_by = Auth::id();
        $new_project->save();

        # If members are provided, add members to the newly created project
        if ($request->input('members')) {
            foreach($request->input('members') as $member) {
                $new_member = new ProjectUser();
                $new_member->user_id = $member;
                $new_member->project_id = $new_project->id;
                $new_member->save();

                $alerts[] = [
                    'message' => "New project has been assigned to you. Project: <b>{$new_project->title}</b>",
                    'action' => 'assigned',
                    'subject_id' => $new_project->id,
                    'type' => 'project',
                    'user_id' => $member,
                    'seen' => 0,
                    'created_at' => Carbon::now()
                ];
            }
            # Generates alerts for all members
            Alert::insert($alerts);
        }

        return redirect()->back()->with('success', 'Project has been created');
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

        $updated_project = Project::find($id);
        $updated_project->title = $request->input('title');
        $updated_project->description = $request->input('description');
        $updated_project->updated_by = Auth::id();
        $updated_project->save();

        # Remove previous records for members
        $old_members = ProjectUser::where('project_id', $id)->delete();

        # If members are provided, add members to the newly created project
        if ($request->input('members')) {
            foreach($request->input('members') as $member) {
                $new_member = new ProjectUser();
                $new_member->user_id = $member;
                $new_member->project_id = $id;
                $new_member->save();
            }
        }

        return redirect()->back()->with('success', 'Project has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        # Remove members
        $remove_members = ProjectUser::where('project_id', $id)->delete();
        $project->delete();

        return redirect()->back()->with('success', 'Project has been deleted');
    }
}
