<?php

namespace App\Jobs;

use App\Models\Site;
use App\Models\SiteStats;
use App\Repositories\SiteStatsRepository;
use Cron\CronExpression;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UptimeMonitorWatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $siteStatsRepository;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SiteStatsRepository $siteStatsRepository)
    {
        $this->siteStatsRepository = $siteStatsRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nextRun = new \Carbon\Carbon((new CronExpression("* * * * *"))->getNextRunDate(now()));

        $sites = Site::where('next_run', $nextRun->subMinute())
                    ->orWhere->lastStatsOverdue()
                    ->orWhere('status', 'pending')
                    ->get()
                    ->each(function($site){
                        UptimeMonitor::dispatch($site, $this->siteStatsRepository)->onQueue('uptime');
                    }); 
    }
}
