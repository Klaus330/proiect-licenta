<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Site;
use App\Models\SiteRoute;
use Illuminate\Support\Facades\Http;

class CrawlSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected const MAX_CRAWLER_DEPTH = 30;
    protected $timeout = 15;
    protected Site $site;
    protected $links = [];
    protected $resgisteredLinks = [];
    protected $alreadyCreatedRoutesArray = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $site = $this->site;
        $this->alreadyCreatedRoutesArray = $this->site->routes_array;
        
        $response = Http::get($site->url);
        $links = $this->fetchAllRelatedLinks($response->body(), $site);
        $this->links = array_merge($this->links, $links);
        $foundOn = $site->url;
        
        foreach($links as $link) {
            $this->crawlUrl($link, $foundOn, $site, self::MAX_CRAWLER_DEPTH);
        }
    }

    protected function crawlUrl($url, $foundOn, $site, $depth)
    {
        if ($depth == 0) {
            return;
        }

        $response = Http::get($url);
        $this->registerUrl($response, $foundOn, $url, $site);
        $foundOn = $url;
        $links = $this->fetchAllRelatedLinks($response->body(), $site);
        $this->links = array_merge($this->links, $links);

        foreach($links as $link) {
            $this->crawlUrl($link, $foundOn, $site, $depth - 1);
        }

        return $response;
    }


    protected function registerUrl($response, $foundOn, $url, $site)
    {
        if(in_array($url, $this->resgisteredLinks))
        {
            return;
        }

        if(in_array($url, $this->alreadyCreatedRoutesArray))
        {
            $route = $site->routes()->where('route', $url)->first();
            $route->update([
                'found_on' =>  $foundOn,
                'http_code' => $response->status(),
                'updated_at' => now()
            ]);
            $this->resgisteredLinks[] = $url;

            return;
        }
        
        SiteRoute::firstOrCreate([
            'site_id' => $site->id,
            'route' => $url,
            'found_on' =>  $foundOn,
            'http_code' => $response->status()
        ]);

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

    public function failed($e)
    {
        var_dump("Job failed");
        // dd($e);
    }
}
