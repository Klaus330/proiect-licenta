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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Site $site)
    {
        // $request->validate([
        //     "name" => "required",
        //     "host" => "required",
        //     "endpoint" => "required",
        //     "method" => "required",
        //     "cronExpression" => Rule::requiredIf($request->input("scheduleType") === "cron"),
        //     "interval" => [Rule::requiredIf($request->input("scheduleType") === "interval")],
        // ]);
    
        // $cronExpression = null;
    
        // if ($request->input("scheduleType") === "inteval") {
        // }
    
        // Scheduler::create([
        //     "name" => $request->input("name"),
        //     "method" => $request->input("method"),
        //     "endpoint" => trim($request->input("endpoint"), "/"),
        //     "alerts" => $request->input("alerts") ?? false,
        //     "failure_number" => $request->input("failure-number"),
        //     "cronExpression" => $cronExpression ?? $request->input("cronExpression"),
        //     "id_website" => $request->input("host"),
        //     "next_run" => (new CronExpression($cronExpression ?? $request->input("cronExpression")))->getNextRunDate(now()),
        // ]);
    
        // if ($request->expectsJson()) {
        //     return response()->json(["success" => "Your scheduler has been created"]);
        // }
    
        // return redirect(route("schedulers.index", ["site" => $site->id]))->with(
        //     "success",
        //     "Your scheduler has been created"
        // );
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function edit(Scheduler $scheduler, Site $site)
    {
        return view("scheduler.edit", compact("site", "scheduler"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Scheduler $scheduler, Site $site)
    {
        // $request->validate([
        //     "name" => "required",
        //     "host" => "required",
        //     "endpoint" => "required",
        //     "method" => "required",
        //     "cronExpression" => Rule::requiredIf($request->input("scheduleType") === "cron"),
        //     "interval" => Rule::requiredIf($request->input("scheduleType") === "interval"),
        //     "intervalMeasure" => Rule::requiredIf($request->input("scheduleType") === "interval"),
        // ]);
    
        // $cronExpression = null;
    
        // if ($request->input("scheduleType") === "inteval") {
        // }
    
        // $scheduler->update([
        //     "name" => $request->input("name"),
        //     "method" => $request->input("method"),
        //     "endpoint" => trim($request->input("endpoint"), "/"),
        //     "alerts" => $request->input("alerts") ?? false,
        //     "failure_number" => $request->input("failure-number"),
        //     "cronExpression" => $cronExpression ?? $request->input("cronExpression"),
        //     "next_run" => (new CronExpression($cronExpression ?? $request->input("cronExpression")))->getNextRunDate(now()),
        // ]);
    
        // if ($request->expectsJson()) {
        //     return response()->json(["success" => "Your scheduler has been updated"]);
        // }
    
        // return redirect(route("schedulers.index", ["site" => $site->id]))->with(
        //     "success",
        //     "Your scheduler has been updated"
        // );
    }

    public function delete(Request $request, Scheduler $scheduler, Site $site)
    {
        return view("scheduler.delete", compact(["site", "scheduler"]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scheduler $scheduler, Request $request)
    {
        // if (!$scheduler->belongsToSite($site)) {
        //     return back();
        // }
    
        // if (!$scheduler->isOwner(auth()->user())) {
        //     return back()->withErrors("You are not authorized for this action.");
        // }
    
        // $scheduler->delete();
    
        // if ($request->expectsJson()) {
        //     return response()->json(["success" => "Your scheduler has been deleted"]);
        // }
    
        // return redirect(route("schedulers.index", ["site" => $site->id]))->with(
        //     "success",
        //     "Your scheduler has been deleted."
        // );
    }
}
