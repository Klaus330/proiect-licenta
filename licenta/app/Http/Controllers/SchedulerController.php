<?php

namespace App\Http\Controllers;

use App\Models\Scheduler;
use App\Models\SchedulerStats;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Repositories\RemoteCodeRepository;

class SchedulerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Site $site)
    {
        return view("scheduler.index", compact(["site"]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Site $site)
    {
        return view("scheduler.create", compact(["site"]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site, Scheduler $scheduler)
    {
        $scheduler->load("host");
        $stats = SchedulerStats::where("scheduler_id", $scheduler->id)
          ->latest()
          ->paginate(10);

        return view("scheduler.show", compact(["scheduler", "site", "stats"]));
    }

    public function settings(Site $site, Scheduler $scheduler)
    {
        return view("scheduler.settings.index", compact(["site", "scheduler"]));
    }

    public function saveSettings(Site $site, Scheduler $scheduler, Request $request, RemoteCodeRepository $remoteCodeRepo)
    {
        $validated = $request->validate([
            'authRoute' => ['required_if:needsAuth,true', 'nullable',],
            'authKeys' => [ 'required_if:needsAuth,true', 'array',],
            'authValues' => [ 'required_if:needsAuth,true', 'array',],
            'file' => [ 'required_if:has_remote_code,true',],
        ]);
        
        $payload = [];

        $hasRemoteCode = isset($request->hasRemoteCode) ? true : false;
        $needsAuth = isset($request->needsAuth) ? true : false;
        
        if($hasRemoteCode && array_key_exists('file', $validated)) {
         $payload['needs_auth'] = false;
         $needsAuth = false;
         $remoteCodeRepo->saveRemoteCodeForScheduler($scheduler, $validated['file']);
        }

        if($needsAuth)
        {
            $authKeys = $validated['authKeys'];
            $authValues = $validated['authValues'];
            $authPayload = array_combine($authKeys, $authValues);

            $payload['auth_route'] = trim($validated['authRoute'], '/');
            $payload['needs_auth'] = true;
            $payload['auth_payload'] = $authPayload;
            $payload['has_remote_code'] = false;
        }

        $payload['payload'] = $request->get('payload');

        $scheduler->update($payload);

        session()->flash("success", "Scheduler settings updated successfully");

        if($request->expectsJson())
        {
            $response = [
                'message' => 'Your settings have been updated successfully',
            ];

            if ($hasRemoteCode) {
                $response['code'] = file_get_contents($scheduler->remote_code_path_with_filename);
            }

            return response()->json($response, 200);
        }

        return redirect()->route("schedulers.settings", ["site" => $site->id, "scheduler" => $scheduler->id]);
    }
}
