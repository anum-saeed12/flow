<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *s
     */
    public function index()
    {
        $listings = [
            (Object) ['id' => 1, 'title' => 'Backlog', 'tasks' => []],
            (Object) ['id' => 2, 'title' => 'In Progress', 'tasks' => []],
            (Object) ['id' => 3, 'title' => 'Completed', 'tasks' => []],
            (Object) ['id' => 4, 'title' => 'Bugs & Issues', 'tasks' => []],
            (Object) ['id' => 5, 'title' => 'Testing', 'tasks' => []],
        ];
        $data = [
            'title'  => 'Tasks',
            'listings' => $listings
        ];
        return view('lists.listings', $data);
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
