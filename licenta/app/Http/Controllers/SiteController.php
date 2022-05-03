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
        $brokenLinks = $site->broken_links->paginate(10);
        $bokenLinksCount = 0;
        if(count($brokenLinks) > 0){
            $links = $site->broken_links->get();
            File::put($site->dir_reports.'broken_links.csv', '');
            $csv = Writer::createFromPath($site->dir_reports.'broken_links.csv', 'w+');
            $csv->insertOne(['Status', 'URL', 'Found on']);
            
            foreach ($links as $brokenLink) {
                $csv->insertOne([$brokenLink->http_code, $brokenLink->route, $site->url]);
            }
            $bokenLinksCount = $links->count();
        }
        $site->loadCount('routes');
        return view('sites.broken-links', compact('site', 'brokenLinks', 'routes', 'bokenLinksCount'));
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
        return view('sites.performance', compact('site'));
    }
}
