<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $select = [
            # User info
            "users.username",
            # Alert info
            "alerts.id",
            "alerts.message",
            "alerts.action",
            "alerts.subject_id",
            "alerts.type",
            "alerts.seen",
            "alerts.user_id",
            "alerts.created_at",
        ];
        $alerts = Alert::select($select)
            ->join('users','users.id','=','alerts.user_id')
            ->where('alerts.user_id',Auth::id())
            ->orderBy('alerts.id','DESC')
            ->paginate($this->count);
        $data = [
            'title' => 'All Alerts',
            'alerts' => $alerts
        ];

        return view('alerts.view', $data);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $find = Alert::find($id);
        if (!$find) return redirect()->back()->with('error', 'Not found');

        if ($find->type == 'task') {
            $select = [
                # User info
                "users.username",
                # Alert info
                "alerts.id",
                "alerts.message",
                "alerts.action",
                "alerts.subject_id",
                "alerts.type",
                "alerts.seen",
                "alerts.user_id",
                "alerts.created_at",
                # Task info
                "tasks.title as subject_title",
                "tasks.description as subject_description",
                "tasks.points as subject_points",
                "tasks.start_date as subject_start_date",
                "tasks.end_date as subject_end_date",
                "tasks.project_id as subject_project_id",
                # Project info
                "projects.title as project_title",
                "projects.description as project_description",
                "projects.created_at as project_creation_date",
            ];
            $alert = Alert::select($select)
                ->join('users', 'users.id', '=', 'alerts.user_id')
                ->leftJoin('tasks', 'tasks.id', '=', 'alerts.subject_id')
                ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                ->where('alerts.user_id', Auth::id())
                ->first();
        } else {
            $select = [
                # User info
                "users.username",
                # Alert info
                "alerts.id",
                "alerts.message",
                "alerts.action",
                "alerts.subject_id",
                "alerts.type",
                "alerts.seen",
                "alerts.user_id",
                "alerts.created_at",
                # Project info
                "projects.title as project_title",
                "projects.description as project_description",
                "projects.created_at as project_creation_date",
            ];
            $alert = Alert::select($select)
                ->join('users', 'users.id', '=', 'alerts.user_id')
                ->leftJoin('projects', 'projects.id', '=', 'alerts.subject_id')
                ->where('alerts.user_id', Auth::id())
                ->first();
        }
        $data = [
            'title' => 'All Alerts',
            'alert' => $alert
        ];

        # Mark as seen
        $find->seen = 1;
        $find->save();

        return view('alerts.show', $data);
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
