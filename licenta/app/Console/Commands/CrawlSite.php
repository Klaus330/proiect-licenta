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
        $site = Site::find($this->argument('site'));
        $this->links = array_merge($this->links, $site->routes_array);
        
        $response = Http::get($site->url);
        $this->info("Crawling {$site->url}");
        $links = $this->fetchAllRelatedLinks($response->body(), $site);
        $this->info("No. links found:".count($links));
        $this->links = array_merge($this->links, $links);
        $foundOn = $site->url;

        foreach($links as $link) {
            $this->info("Crawling {$link}");
            $this->crawlUrl($link, $foundOn, $site, 30);
            $this->info("No. links found:".count($links));
        }

        return Command::SUCCESS;
    }


    protected function crawlUrl($url, $foundOn, $site, $depth)
    {
        if ($depth == 0) {
            return;
        }

        $this->info("Crawling {$url}");
        $response = Http::get($url);
        $this->registerUrl($response, $foundOn, $url, $site);
        $foundOn = $url;
        $links = $this->fetchAllRelatedLinks($response->body(), $site);
        $this->info("No. links found: ".count($links));
        $this->links = array_merge($this->links, $links);

        foreach($links as $link) {
            $this->crawlUrl($link, $foundOn, $site, $depth - 1);
        }        
    }


    protected function registerUrl($response, $foundOn, $url, $site)
    {
        if(in_array($url, $this->resgisteredLinks))
        {
            return;
        }

        $this->info("Registering {$url}");
        SiteRoute::firstOrCreate([
            'site_id' => $site->id,
            'route' => $url,
            'found_on' =>  $foundOn,
            'http_code' => $response->status()
        ]);
        $this->info("{$url} registered");

        $this->resgisteredLinks[] = $url;
    }

    protected function fetchAllRelatedLinks($content, $site){
        preg_match_all('/<a.*?href="(.*?)".*?>/', $content, $matches);
        $links = array_values(array_filter($matches[1], function($el) use ($site) {
            return (strpos($el, $site->url) === 0 || substr($el, 0, 1) === '/' || substr($el, 0, 1) === '?' ) && !in_array($el, $this->links);
        }));

        $links = array_map(function($el) use ($site) {
            if(strpos($el, $site->url) !== 0){
                return $site->url . $el;
            }
            
            return $el;
        }, $links);

        $links = array_diff($links, $this->links);
        return $links;
    }
}
