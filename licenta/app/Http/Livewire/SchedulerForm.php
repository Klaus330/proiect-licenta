<?php

namespace App\Http\Livewire;

use App\Models\Scheduler;
use Cron\CronExpression;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SchedulerForm extends Component
{
    public $site;
    public $schedulerType;
    public $name;
    public $schedulerEndpoint;
    public $method;
    public $interval;
    public $cronExpression;
    public $cronExpressions;


    public function mount()
    {
        $this->schedulerType = 'cron';
        $this->name= '';
        $this->schedulerEndpoint = '/';
        $this->method = 'GET';
        $this->interval = '60';
        $this->cronExpression = '* * * * *';

        $this->cronExpressions = [
            '60' => '* * * * *',
            '300' => '*/5 * * * *',
            '3600' => '0 * * * *'
        ];
    }

    public function createScheduler()
    {
        $validatedData = $this->validate([
            "name" => "required|unique:schedulers",
            "schedulerEndpoint" => "required",
            "method" => "required",
            "cronExpression" => Rule::requiredIf($this->schedulerType === Scheduler::TYPE_CRON_EXPRESSION),
            "interval" => [Rule::requiredIf($this->schedulerType === Scheduler::TYPE_INTERVAL)],
        ]);

        $cronExpression = $validatedData['cronExpression'];

        if($this->schedulerType === Scheduler::TYPE_INTERVAL)
        {
            $cronExpression = $this->cronExpressions[$this->interval];
        }

        $scheduler = Scheduler::create([
            "name" => $validatedData['name'],
            "method" => $validatedData['method'],
            "endpoint" => trim($validatedData['schedulerEndpoint'], "/"),
            "cronExpression" => $cronExpression,
            "site_id" => $this->site->id,
            "next_run" => (new CronExpression($cronExpression))->getNextRunDate(now()),
        ]);

        $this->emit('schedulerCreated', ['scheduler' => $scheduler]);
        $this->dispatchBrowserEvent('flash', ['message' => 'Scheduler Created']);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.scheduler-form');
    }
}
