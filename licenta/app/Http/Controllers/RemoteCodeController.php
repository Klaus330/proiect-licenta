<?php

namespace App\Http\Controllers;

use App\Models\RemoteCode;
use App\Models\Scheduler;
use App\Repositories\RemoteCodeRepository;
use Illuminate\Http\Request;

class RemoteCodeController extends Controller
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
    public function create(Request $request, RemoteCodeRepository $repo)
    {
        $validated = $request->validate([
            'file' => 'required|file',
            'scheduler' => 'required|integer',
        ]);

        $scheduler = Scheduler::find($validated['scheduler']);

        $repo->saveRemoteCodeForScheduler($scheduler, $request->file('file'));

        session()->flash("success", "Your file has been uploaded successfully");
        return response()->json(['code' => file_get_contents($scheduler->remote_code_path_with_filename)], 201);
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
