<?php

namespace App\Http\Controllers;

use App\Models\Scheduler;
use App\Models\SchedulerStats;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use App\Models\Site;

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

    public function saveSettings(Site $site, Scheduler $scheduler, Request $request)
    {
        $validated = $request->validate([
            'auth_route' => [
                'required_if:needs_auth,true',
                'nullable',],
            'authKeys' => [
                'required_if:needs_auth,true',
                'array',],
            'authValues' => [
                'required_if:needs_auth,true',
                'array',],
        ]);

        $payload = [];

        $needsAuth = isset($request->needs_auth) ? true : false;
        if($needsAuth)
        {
            $authKeys = $validated['authKeys'];
            $authValues = $validated['authValues'];
            $authPayload = array_combine($authKeys, $authValues);

            $payload['auth_route'] = trim($validated['auth_route'], '/');
            $payload['needs_auth'] = true;
            $payload['auth_payload'] = $authPayload;
        }
        
        $payload['payload'] = $request->get('payload');

        $scheduler->update($payload);

        session()->flash("success", "Scheduler settings updated successfully");
        return redirect()->route("schedulers.settings", ["site" => $site->id, "scheduler" => $scheduler->id]);
    }
}
