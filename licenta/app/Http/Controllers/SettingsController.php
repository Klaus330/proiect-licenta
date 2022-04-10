<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Repositories\SiteRepository;

class SettingsController extends Controller
{
    public function index(Site $site)
    {
        return view('sites.settings.index', compact('site'));
    }
    
    // public function notifications()
    // {
    //     return view("settings.notifications");
    // }

    // public function teams()
    // {
    //     return view("settings.teams");
    // }

    // public function billing()
    // {
    //     return view("settings.billing");
    // }

    // public function integrationWithSlack()
    // {
    //     return view("settings.integrations.slack");
    // }

    public function uptime(Site $site)
    {
        return view('sites.settings.uptime', compact('site'));
    }

    public function sslCertificate(Site $site)
    {
        return view('sites.settings.ssl-certificate', compact('site'));
    }


    public function updateGeneral(Request $request, Site $site, SiteRepository $siteRepository)
    {
        $request->validate([
            'schema' => 'required',
            'host' => 'required'
        ]);

        $response = $siteRepository->updateSettings($site, $request->request->all());

        if (!$response) {
            return back()->withErrors("Something went wrong!");
        }

        session()->flash('success', "Your site general data was updated");
        return redirect()->route("settings.general", ['site' => $site->id]);
    }

    public function updateUptime(Request $request, Site $site)
    {
        $request->validate([
            'verb' => 'required'
        ]);

        $payload = [];
        if ($request->has('field-names') && $request->has('field-values')) {
            $payload = array_combine($request->input('field-names'), $request->input('field-values'));
        }

        $response = $site->update([
            'verb' => $request->input('verb'),
            'timeout' => $request->input('timeout') ?? 0,
            'check' => $request->input('check'),
            'payload' => $payload,
        ]);

        if (!$response) {
            return back()->withErrors("Something went wrong!");
        }

        session()->flash('success', "Your site uptime data was updated");
        return redirect(route("settings.uptime", ['site' => $site->id]));
    }

    public function updateSslCertificate(Request $request, Site $site)
    {
        
    }
}
