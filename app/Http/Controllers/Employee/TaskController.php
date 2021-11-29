<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use Carbon\Carbon;
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
        //
    }

    public function completed(Request $request, $task_id)
    {
        $task = Task::find($task_id);
        if (!$task) return redirect()->back()->with('error','Task not found');
        if ($task->completed == 1) return redirect()->back()->with('error','Task has already been completed');

        $update_points = TaskUser::where('task_id', $task_id)
            ->where('user_id', Auth::id())
            ->update(['points' => $task->points]);
        $task->completed = 1;
        $task->save();
        $alerts = [];
        # Fetch username
        $username = Auth::user()->username;
        # Fetch project details
        $project = Project::find($task->project_id);
        # Fetch all members of the task
        $members = TaskUser::where('task_id', $task_id)->get();
        foreach($members as $member) {
            if ($member->user_id == Auth::id()) {
                # Creates an array of alert
                $alerts[] = [
                    'message' => "You have been awarded <b>{$task->points} points</b> for completing the task for project <b>{$project->title}</b>",
                    'action' => 'completed',
                    'subject_id' => $task_id,
                    'type' => 'task',
                    'user_id' => Auth::id(),
                    'seen' => 0,
                    'created_at' => Carbon::now()
                ];
                continue;
            }
            # Creates an array of alert
            $alerts[] = [
                'message' => "Task has been marked as completed by <b>{$username}</b> for project <b>{$project->title}</b>",
                'action' => 'completed',
                'subject_id' => $task_id,
                'type' => 'task',
                'user_id' => $member->user_id,
                'seen' => 0,
                'created_at' => Carbon::now()
            ];
        }
        # Creates an array of alert
        $alerts[] = [
            'message' => ucfirst(Auth::user()->user_role)." was awarded <b>{$task->points} points</b> for completing a task for <b>{$project->title}</b>",
            'action' => 'completed',
            'subject_id' => $task_id,
            'type' => 'task',
            'user_id' => $project->created_by,
            'seen' => 0,
            'created_at' => Carbon::now()
        ];
        # Generates alerts for all members
        Alert::insert($alerts);

        if ($request->wantsJson()) return showSuccessMessage("Task has been marked as completed");

        return redirect()->back()->with('success', 'Task has been marked as completed');
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
