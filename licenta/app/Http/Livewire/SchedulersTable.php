<?php

namespace App\Http\Livewire;

use App\Models\Scheduler;
use Livewire\Component;

class SchedulersTable extends Component
{
    public $site;


    public $listeners = [
        'schedulerCreated' => 'render'
    ];

    public function render()
    {
        return view('livewire.schedulers-table', [
            'schedulers' => Scheduler::where("site_id", $this->site->id)
            ->with("host")
            ->paginate(10)
        ]);
    }
}
