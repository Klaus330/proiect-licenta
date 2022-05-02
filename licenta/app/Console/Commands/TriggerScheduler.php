<?php

namespace App\Console\Commands;

use App\Models\Scheduler;
use App\Models\SchedulerStats;
use Cron\CronExpression;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class TriggerScheduler extends Command
{
    protected const ERROR_CODE = '500';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler {scheduler}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It triggers an HTTP request to a specified scheduler';
    
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
        // TODO: REFACTOR USING EVENTS
        $this->info('Changing the next-run attribute');

        $scheduler = $this->argument('scheduler');
        $scheduler = Scheduler::find($scheduler);

        if(empty($scheduler))
        {
            $this->error('No scheduler found');

            return Command::FAILURE;
        }

        $this->executed_at = now();
        $this->nextRunDate = (new CronExpression($scheduler->cronExpression))->getNextRunDate(now());
        $scheduler->update(['next_run' => $this->nextRunDate]);

        $this->info('Generating the url');

        $url = "{$scheduler->host->url}/{$scheduler->endpoint}";

        $this->info('Making the request');

        $startedAt = new \DateTime();
        $start = microtime(true);

        try {

            [$response, $statsAttributes] = $this->makeTheRequest($scheduler, $url, $startedAt, $start);

            $attributes = array_merge(
                [
                    'executed_at' => $this->executed_at->toDateTime(),
                    'scheduler_id' => $scheduler->id,
                    'response_body' => $response->getBody()->getContents(),
                    'status_code' => $response->getStatusCode()
                ],
                $statsAttributes
            );

            SchedulerStats::create($attributes);

            $scheduler->update(['status' => $response->getStatusCode()]);

            $this->info('Scheduled Job Finished');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("An error occured: {$e->getMessage()}");
            if ($e instanceof RequestException) {
                $response = $e->getResponse();
                
                $lastStatusCode = $scheduler->latestStats()->status_code ?? self::ERROR_CODE;
                $endedAt = new \DateTime();
                $end = microtime(true);
                SchedulerStats::create([
                    'executed_at' => $this->executed_at,
                    'headers' => json_encode($response->getHeaders()),
                    'scheduler_id' => $scheduler->id,
                    'response_body' => $response->getBody(),
                    'status_code' => $response->getStatusCode(),
                    'started_at' => $startedAt,
                    'ended_at' => $endedAt,
                    'duration' => floatval($end - $start)
                ]);

                $this->nextRunDate = (new CronExpression($scheduler->cronExpression))->getNextRunDate(now());
                $scheduler->update(['next_run' => $this->nextRunDate]);

                if ($this->isErrorStatusCode($lastStatusCode) && $scheduler->canSendNotification()) {
                    $scheduler->update(['emailed_at' => now()]);
                    $scheduler
                        ->owner()
                        ->notify(
                            new \App\Notifications\SchedulerFailed(
                                $scheduler,
                                $response
                            )
                        );
                }
            }
            return Command::FAILURE;
        }
    }

    protected function makeTheRequest($scheduler, $url, $startedAt, $start)
    {
        $statsAttributes = [];
        $client = new Client();
        $response = $client->request($scheduler->method, $url, [
            'on_stats' => function (TransferStats $stats) use (
                $startedAt,
                $start,
                &$statsAttributes
            ) {
                $response = $stats->getResponse();
                $request = $stats->getRequest();
                $handlerStats = $stats->getHandlerStats();
                $endedAt = new \DateTime();
                $end = microtime(true);
                $duration = $end - $start;
                $statsAttributes = [
                    'headers' => json_encode($response->getHeaders()),
                    'handler_stats' => json_encode($handlerStats),
                    'transfer_time' => $stats->getHandlerStat(
                        'transfer_time'
                    ),
                    'started_at' => $startedAt,
                    'ended_at' => $endedAt,
                    'duration' => floatval($duration)
                ];
            },
            'allow_redirects' => true,
            'http_errors' => true
        ]);

        return [$response, $statsAttributes];
    }

    protected function isErrorStatusCode($code)
    {
        return $code >= 400 && $code < 600;
    }
}
