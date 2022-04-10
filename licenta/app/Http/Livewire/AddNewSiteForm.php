<?php

namespace App\Http\Livewire;

use App\Exceptions\SiteDuplication;
use Livewire\Component;
use App\Repositories\SiteRepository;
use App\Models\Site;
use App\Rules\ValidWebsite;
use Illuminate\Support\Facades\Artisan;

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
            // Dispatch Site created event
            $this->emit('siteCreated');
            
            // Check the ssl certificate
            Artisan::queue("ssl:check", ["site" => $site->id]);

            // Notify the user
            // $when = now()->addMinutes(15);
            // auth()->user()->notify((new UptimeMonitorRegistered())->delay($when));

        } catch (SiteDuplication $e) {
            $this->addError('url', $e->getMessage());
        } finally {
            $this->reset();
        }
    }
}
