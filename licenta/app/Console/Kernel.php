<?php

namespace App\Console;

use App\Jobs\CrawlersWatcher;
use App\Jobs\LighthouseReportWatcher;
use App\Jobs\SchedulersWatcher;
use App\Jobs\SslCertificateExpirationWatcher;
use App\Jobs\SslCertificateWatcher;
use App\Jobs\UptimeMonitorWatcher;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(SchedulersWatcher::class)->everyMinute();
        $schedule->job(UptimeMonitorWatcher::class)->everyMinute();
        $schedule->job(SslCertificateWatcher::class)->everyMinute();
        $schedule->job(SslCertificateExpirationWatcher::class)->daily();
        $schedule->job(CrawlersWatcher::class)->daily();
        $schedule->job(LighthouseReportWatcher::class)->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
