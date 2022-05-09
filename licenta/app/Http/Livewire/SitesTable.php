<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Site;

use App\Repositories\SiteStatsRepository;
use Illuminate\Support\Facades\Cookie;

class SitesTable extends Component
{
    use WithPagination;

    protected $listeners = [
        'siteCreated' => 'render'
    ];

    public function render()
    {
        return view('livewire.sites-table', [
            'sites' => Site::where('user_id', auth()->user()->id)->paginate(10)
        ]);
    }

    public function dispatchUptimeEvent($siteId)
    {
        if(Cookie::has('too_many_requests'))
        {
            return;
        }

        $site = Site::findOrFail($siteId);
        dispatch(new \App\Jobs\UptimeMonitor($site, resolve(SiteStatsRepository::class)))->onQueue('uptime');
        cookie("too_many_requests", sha1("true"), 10);
    }
}
