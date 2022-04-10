<?php

namespace App\Jobs;

use App\Models\Scheduler;
use Cron\CronExpression;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;


class SchedulersWatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nextRun = new \Carbon\Carbon((new CronExpression("* * * * *"))->getNextRunDate(now()));

        $schedulers = Scheduler::where("next_run", $nextRun->subMinute())
                    ->orWhereDoesntHave("stats")
                    ->get();

        foreach ($schedulers as $scheduler) {
          Artisan::queue("scheduler", ["scheduler" => $scheduler->id])->onQueue("schedulers");
        }
    }
}
