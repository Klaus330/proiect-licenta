<?php

namespace App\Jobs;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlersWatcher implements ShouldQueue
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
        Site::chunk(100, function ($sites) {
            $sites->each(Function($site){
                $route = $site->routes()->first();

                if ($route != null && $route->updated_at->diffInDays() <= 1)  {
                    return;
                }
                
                // dispatch crawl site job
                CrawlSite::dispatch($site)->onQueue('crawlers');
            });
        });
    }
}
