<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        # Expected parameters
        # 1. target_id (Project/Task)
        # 2. user_id
        # 3. Type

        $request->validate([
            'target_id' => 'required',
            'user_id'   => 'required',
            'type'      => 'required|in:project,task'
        ]);

        $target_class = strtolower($request->input('type')) . 'User';
        $target_type  = strtolower($request->input('type')) . '_id';

        $new_assignment = new $target_class();
        $new_assignment->user_id = $request->input('user_id');
        $new_assignment->{$target_type} = $request->input('target_id');
        $new_assignment->save();

        if (!$request->wantsJson()) return redirect()->back()->with('success', 'Member added to the ' . ucfirst($request->input('type')));

        return showSuccessMessage('Member added to the ' . ucfirst($request->input('type')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(Request $request)
    {
        # Expected parameters
        # 1. target_id (Project/Task)
        # 2. user_id
        # 2. Type

        $request->validate([
            'target_id' => 'required',
            'user_id' => 'required',
            'type' => 'required|in:project,task'
        ]);

        $target_class = strtolower($request->input('type')) . 'User';
        $target_type = strtolower($request->input('type')) . '_id';

        $user_deleted = $target_class::where($target_type, $request->input('target_id'))
            ->where('user_id', $request->input('user_id'))
            ->delete();

        if (!$request->wantsJson()) return redirect()->back()->with('success', 'Member deleted from the ' . ucfirst($request->input('type')));

        return showSuccessMessage('Member deleted from the ' . ucfirst($request->input('type')));
    }
}
