<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Site;

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
}
