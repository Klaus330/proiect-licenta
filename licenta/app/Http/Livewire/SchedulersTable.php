<?php

namespace App\Http\Livewire;

use App\Models\Scheduler;
use Livewire\Component;

class SchedulersTable extends Component
{
    public $site;

    public $listeners = [
        'schedulerCreated' => 'render',
        'deleteScheduler'
    ];

    public function render()
    {
        return view('livewire.schedulers-table', [
            'schedulers' => Scheduler::where("site_id", $this->site->id)
            ->with("host")
            ->paginate(10)
        ]);
    }

    public function deleteScheduler($payload)
    {
        $scheduler = Scheduler::find($payload['payload']['schedulerId']);

        // Check authentications


        $scheduler->delete();
        session()->flash('success', 'Scheduler deleted.');


        return redirect()->route('schedulers.index', ['site' => $this->site->id]);
    }
}
