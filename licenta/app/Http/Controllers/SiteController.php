<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use File;

class SiteController extends Controller
{
    public function index()
    {
        return view('sites.index');
    }

    public function show(Site $site)
    {
        $lastIncidents = $site->last_incidents;
        return view("sites.show", compact("site", "lastIncidents"));
    }
    
    public function store()
    {
        
    }
    
    public function edit()
    {
        
    }

    public function update()
    {
        
    }

    public function overview(Site $site)
    {
        $latestStats = $site->getLastMonthMonitoringInfo();
        $lastIncident = $site->last_incident;
        
        return view('sites.overview', compact('site', 'latestStats', 'lastIncident'));
    }

    public function delete(Site $site)
    {
        return view('sites.destroy', compact('site'));
    }

    public function destroy(Site $site)
    {
        if (auth()->user()->id !== $site->owner()->first()->id) {
            return back()->withErrors(["You are not allowed to modify this site"]);
        }
    
        $site->delete();
    
        return redirect()->route('sites.index')->with("success", "The site has been deleted");
    }
    
    public function brokenLinks(Site $site)
    {
        $routes = $site->routes()->where('http_code', 'like', '2__')->paginate(15);
        $brokenLinks = $site->broken_links;

        if(count($brokenLinks) > 0){
            File::put($site->dir_reports.'broken_links.csv', '');
            $csv = Writer::createFromPath($site->dir_reports.'broken_links.csv', 'w+');
            $csv->insertOne(['Status', 'URL', 'Found on']);
            
            foreach ($brokenLinks as $brokenLink) {
                $csv->insertOne([$brokenLink->http_code, $brokenLink->route, $site->url]);
            }
        }
        $site->loadCount('routes');
        return view('sites.broken-links', compact('site', 'brokenLinks', 'routes'));
    }

    public function downloadBrokenLinks(Site $site)
    {
        return response()->download($site->dir_reports.'broken_links.csv', 'broken_links_'.date('Ymdhis').'.csv');
    }

    public function sslCertificateHealth(Site $site)
    {
        $site->load('sslCertificate');        
        return view('sites.ssl-certificate-health', compact('site'));
    }

    public function performance(Site $site)
    {
        $stats = $site->stats()->where('created_at', '>=', now()->subHour())->get();
        
        if($stats->isNotEmpty()){
            $created_at = $stats->pluck('created_at')->reverse()->toArray();
            $created_at = array_values(array_filter($created_at, function($created_at) {
                return $created_at->minute % 5 === 0;
            }));

            $created_at = array_map(function($created_at) {
                return $created_at->toDateTimeString();
            }, $created_at);
            
        
            $data = [
                'dates' => $created_at,
                'dns_lookup' => $stats->map(function ($stat) {return $stat->dns_lookup / 1000;})->reverse()->values()->toArray(),
                'content_download' => $stats->map(function ($stat) {return ($stat->total_time - $stat->start_transfer_time) / 1000;})->reverse()->values()->toArray(),
                'tls_time' => $stats->map(function ($stat) {return ($stat->appconnect_time * 1000000 - $stat->dns_lookup ) / 1000;})->reverse()->values()->toArray(),
                'transfer_time' => $stats->map(function ($stat) {return ($stat->start_transfer_time - $stat->appconnect_time * 1000000 ) / 1000;})->reverse()->values()->toArray(),
                'total_time' =>  $stats->map(function ($stat) {return $stat->total_time / 1000;})->reverse()->values()->toArray(),
            ];
        }else{
            $data = [];
        }

        return view('sites.performance', compact('site', 'stats', 'data'));
    }
}
