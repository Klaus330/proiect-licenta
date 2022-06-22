<?php

namespace App\Console\Commands;

use App\Models\Scheduler;
use App\Models\SchedulerStats;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class RunRemoteCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remote:run {scheduler}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $isolateScriptPath = './public/remote-code/index.js';
    protected $timeout = 120;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $scheduler = Scheduler::find($this->argument('scheduler'));
        
        if($scheduler === null)
        {
            return Command::FAILURE;
        }

        $executedat = now();
        $startedAt = new \DateTime();
        $start = microtime(true);

        $processPayload = [
            'node',
            $this->isolateScriptPath,
            "--path=" . $scheduler->remote_code_path_with_filename
        ];
        
        $process = new Process($processPayload);
        
        $process->setTimeout($this->timeout)
                ->run(null, []);
        
        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
            $this->wirteStatus($startedAt, $start, $executedat, $scheduler, $process, 500);
            return Command::FAILURE;
        }


        $this->wirteStatus($startedAt, $start, $executedat, $scheduler, $process, 200);
        
        $this->info("Successfully ran remote code");

        return Command::SUCCESS;
    }

    protected function wirteStatus($startedAt, $start, $executedat, $scheduler, $process, $code)
    {
        $endedAt = new \DateTime();
        $end = microtime(true);

        SchedulerStats::create([
            'executed_at' => $executedat,
            'headers' => json_encode([]),
            'scheduler_id' => $scheduler->id,
            'response_body' => $process->getOutput(),
            'status_code' => $code,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration' => floatval($end - $start)
        ]);

        $this->nextRunDate = (new CronExpression($scheduler->cronExpression))->getNextRunDate(now());
        $scheduler->update(['next_run' => $this->nextRunDate]);
    }
}
