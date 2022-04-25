<?php

namespace App\Http\Livewire;

use App\Exceptions\SiteDuplication;
use Livewire\Component;
use App\Repositories\SiteRepository;
use App\Models\Site;
use App\Rules\ValidWebsite;
use Illuminate\Support\Facades\Artisan;
use App\Notifications\UptimeMonitorRegistered;

class AddNewSiteForm extends Component
{
    public $url;

    public function render()
    {
        return view('livewire.add-new-site-form');
    }

    public function addNewSite(SiteRepository $siteRepository)
    {
        $data = $this->validate([
            'url' => ['required', new ValidWebsite]
        ]);

        try {
            $site = $siteRepository->createOrFail($data);
            
            if(! is_dir($site->dir_reports)){
                mkdir($site->dir_reports);
            }

            // Dispatch Site created event
            $this->emit('siteCreated');
            
            // Check the ssl certificate
            Artisan::queue("ssl:check", ["site" => $site->id]);

            // Dispatch Crawler Job
            dispatch(new \App\Jobs\CrawlSite($site))->onQueue('crawlers');

            // Notify the user
            $when = now()->addMinutes(15);
            $site->owner->notify((new UptimeMonitorRegistered())->delay($when));

        } catch (SiteDuplication $e) {
            $this->addError('url', $e->getMessage());
        } finally {
            $this->reset();
        }
    }
}
