<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        # 1. Title (Project Title)_
        # 2. Description
        # 3. Points
        # 4. Start Date (Optional)
        # 5. End Date (Optional)

        $request->validate([
            'title' => 'required|max:150',
            'description' => 'required|max:300',
            'points' => 'required|numeric',
            'project_id' => 'required|exists:App\Models\Project,id',
            'start_date'=> 'required',
            'end_date'=> 'required',
        ]);

        $new_task = new Task();
        $new_task->title = $request->input('title');
        $new_task->description = $request->input('description');
        $new_task->points = $request->input('points');
        $new_task->project_id = $request->input('project_id');
        $request->start_date && $new_task->start_date = $request->input('start_date');
        $request->end_date && $new_task->end_date = $request->input('end_date');
        $new_task->created_by = Auth::id();

        $new_task->save();

        $project = Project::find($request->input('project_id'));

        $alerts = [];

        # If members are provided, add members to the newly created project
        if ($request->input('members')) {
            foreach($request->input('members') as $member) {
                $new_member = new TaskUser();
                $new_member->user_id = $member;
                $new_member->task_id = $new_task->id;
                $new_member->points = 0;
                $new_member->created_by = Auth::id();
                $new_member->save();
                # Creates an array of alert
                $alerts[] = [
                    'message' => "New task has been added and assigned to you for <b>{$project->title}</b>",
                    'action' => 'assigned',
                    'subject_id' => $new_task->id,
                    'type' => 'task',
                    'user_id' => $member,
                    'seen' => 0,
                    'created_at' => Carbon::now()
                ];
            }
            # Generates alerts for all members
            Alert::insert($alerts);
        }

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
     */
    public function update(Request $request, $id)
    {
        # Expected Parameters
        # 1. Title (Project Title)_
        # 2. Description
        # 3. Points
        # 4. Start Date (Optional)
        # 5. End Date (Optional)

        $request->validate([
            'title' => 'required|max:150',
            'description' => 'required|max:300',
            'points' => 'required|numeric',
            'project_id' => 'required|exists:App\Models\Project,id'
        ]);

        $updated_task = Task::find($id);

        # Checks if the task has been completed
        if ($updated_task->completed == 1) return redirect()->back()->with('error', 'Task has already been completed!');

        $updated_task->title = $request->input('title');
        $updated_task->description = $request->input('description');
        $updated_task->points = $request->input('points');
        $updated_task->project_id = $request->input('project_id');
        $request->start_date && $updated_task->start_date = $request->input('start_date');
        $request->end_date && $updated_task->end_date = $request->input('end_date');
        $updated_task->created_by = Auth::id();
        $updated_task->save();

        $project = Project::find($request->input('project_id'));
        # Fetch old members
        $old_members = TaskUser::select(DB::raw('GROUP_CONCAT(user_id) as members'))->where('task_id', $id)->first();
        $old_members = explode(',', $old_members->members);
        # Remove previous records for members
        $delete_old_members = TaskUser::where('task_id', $id)->delete();

        # If members are provided, add members to the project
        if ($request->input('members')) {
            foreach($request->input('members') as $member) {
                $new_member = new TaskUser();
                $new_member->user_id = $member;
                $new_member->task_id = $updated_task->id;
                $new_member->points = 0;
                $new_member->created_by = Auth::id();
                $new_member->save();

                if (in_array($member, $old_members)) {
                    $key = array_search('2', $old_members);
                    unset($old_members[$key]);
                } else {
                    # Creates an array of alert
                    $alerts[] = [
                        'message' => "A task has been assigned to you for <b>{$project->title}</b>",
                        'action' => 'assigned',
                        'subject_id' => $updated_task->id,
                        'type' => 'task',
                        'user_id' => $member,
                        'seen' => 0,
                        'created_at' => Carbon::now()
                    ];
                }
            }
            foreach($old_members as $removed_member) {
                # Creates an array of alert
                $alerts[] = [
                    'message' => "You have been removed from a task for <b>{$project->title}</b>",
                    'action' => 'removed',
                    'subject_id' => $updated_task->id,
                    'type' => 'task',
                    'user_id' => $removed_member,
                    'seen' => 0,
                    'created_at' => Carbon::now()
                ];
            }
            # Generates alerts for all members
            Alert::insert($alerts);
        }

        return redirect()->back()->with('success', 'Task has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $project = Task::find($id);
        # Remove members
        $remove_members = TaskUser::where('project_id', $id)->delete();
        $project->delete();

        return redirect()->back()->with('success', 'Task has been deleted');
    }
}
