<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use App\Models\SiteRoute;
use Illuminate\Support\Facades\Http;

class CrawlSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:site {site=1: The id of the site}';
    protected $links = ['/'];
    protected $resgisteredLinks = ['/'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a crawler for a site';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();        
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $site = Site::first();
        dispatch(new \App\Jobs\CrawlSite($site))->onQueue('crawlers');

        return Command::SUCCESS;
    }
}
