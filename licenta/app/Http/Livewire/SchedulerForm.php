<?php

namespace App\Http\Livewire;

use App\Models\Scheduler;
use App\Models\Site;
use Cron\CronExpression;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SchedulerForm extends Component
{
    public Scheduler $scheduler;
    public Site $site;
    public $scheduleType;
    public $method;
    public $interval;
    public $cronExpressions;
    public $mode;

    public $listeners = ['updateScheduler'];

    public function mount(Scheduler $scheduler)
    {
        $this->scheduler = $scheduler;
        $this->scheduleType = Scheduler::TYPE_CRON_EXPRESSION;
        $this->interval = '60';
        $this->method = $scheduler->method ?? 'GET';
        $this->mode = 'new';

        $this->cronExpressions = [
            '60' => '* * * * *',
            '300' => '*/5 * * * *',
            '3600' => '0 * * * *'
        ];
    }

    public function createScheduler()
    {    
        $validatedData = $this->validate();
        
        $cronExpression = $validatedData['scheduler']['cronExpression'];

        if($this->scheduleType === Scheduler::TYPE_INTERVAL)
        {
            $cronExpression = $this->cronExpressions[$this->interval];
        }

        $this->scheduler->method = $this->method;
        $this->scheduler->cronExpression = $cronExpression;
        $this->scheduler->endpoint = trim($this->scheduler->endpoint, '/');
        $this->scheduler->site_id = $this->site->id;
        $this->scheduler->next_run = (new CronExpression($cronExpression))->getNextRunDate(now());
        $this->scheduler->save();

        $this->emit('schedulerCreated', ['scheduler' => $this->scheduler]);
        session('flash', ['message' => 'Scheduler Created']);

        return redirect()->route('schedulers.index', ['site' => $this->site]);
    }

    public function render()
    {
        return view('livewire.scheduler-form');
    }

    public function rules()
    {
        return [
            "scheduler.name" => "required",
            "scheduler.endpoint" => "required",
            "method" => "required",
            "scheduler.cronExpression" => Rule::requiredIf($this->scheduleType === Scheduler::TYPE_CRON_EXPRESSION),
            "interval" => [Rule::requiredIf($this->scheduleType === Scheduler::TYPE_INTERVAL)],
        ];
    }

    public function updateScheduler($data)
    {
        $this->scheduler = Scheduler::find($data['scheduler']);
        $this->mode = 'edit';
    }

    public function resetForm()
    {
        $this->scheduler = new Scheduler();
        $this->mode = 'new';
    }
}
