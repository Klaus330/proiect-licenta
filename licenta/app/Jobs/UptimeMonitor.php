<?php

namespace App\Jobs;

use App\Models\Site;
use App\Notifications\ChangeInPerformance;
use App\Notifications\MalformedUptimeResponse;
use App\Notifications\SiteDown;
use App\Notifications\SiteRecovered;
use App\Repositories\SiteStatsRepository;
use Cron\CronExpression;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\TransferStats;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Http\Message\ResponseInterface;

class UptimeMonitor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 1;
    public $maxException = 2;

    protected Site $site;
    protected SiteStatsRepository $statsRepo;
    protected $schedule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Site $site, SiteStatsRepository $statsRepo)
    {
        $this->site = $site;
        $this->statsRepo = $statsRepo;
        $this->schedule = new CronExpression("* * * * *");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $startedAt = new \DateTime();
        $start = microtime(true);

        $client = new Client();
        $response = $client->request($this->site->verb, $this->site->url, [
            RequestOptions::HEADERS => $this->site->headers ?? [],
            RequestOptions::FORM_PARAMS => $this->site->acceptsGet() ? [] : $this->site->payload,
            RequestOptions::ON_STATS => function (TransferStats $stats) use ($startedAt, $start) {
                $response = $stats->getResponse();
                $request = $stats->getRequest();

                $endedAt = new \DateTime();
                $end = microtime(true);
                $duration = $end - $start;

                $attributes = [
                    "site_id" => $this->site->id,
                    "dns_lookup" => $stats->getHandlerStat("namelookup_time"),
                    "total_time" => $stats->getHandlerStat("total_time_us"),
                    "connect_time" => $stats->getHandlerStat("connect_time_us"),
                    "speed_download" => $stats->getHandlerStat("speed_download"),
                    "size_download" => $stats->getHandlerStat("size_download"),
                    "header_size" => $stats->getHandlerStat("header_size"),
                    "request_size" => $stats->getHandlerStat("request_size"),
                    "content_type" => $stats->getHandlerStat("content_type"),
                    "content_length" => $stats->getHandlerStat("download_content_length"),
                    "primary_port" => $stats->getHandlerStat("primary_port"),
                    "appconnect_time" => $stats->getHandlerStat("appconnect_time"),
                    "start_transfer_time" => $stats->getHandlerStat("starttransfer_time"),
                    "http_code" => $stats->getHandlerStat("http_code"),
                    "pretransfer_time" => $stats->getHandlerStat("pretransfer_time_us"),
                    "redirect_count" => $stats->getHandlerStat("redirect_count"),
                    "redirect_time" => $stats->getHandlerStat("redirect_time"),
                    "server" => $response->getHeaderLine("Server"),
                    "date" => $response->getHeaderLine("Date"),
                    "connection" => $response->getHeaderLine("Connection"),
                    "protocol_version" => $request->getProtocolVersion(),
                    "http_version" => $stats->getHandlerStat("http_version"),
                    "scheme" => $stats->getHandlerStat("scheme"),
                    "headers" => json_encode($response->getHeaders()),
                    "reason_phrase" => $response->getReasonPhrase(),
                    "user_agent" => $request->getHeaderLine("User-Agent"),
                    "started_at" => $startedAt,
                    "ended_at" => $endedAt,
                    "duration" => floatval($duration),
                  ];

                //   Create stats
                $this->statsRepo->create($attributes);
            },
            RequestOptions::ALLOW_REDIRECTS => true,
        ]);

        $this->performChecks($response);

        $nextRun = $this->schedule->getNextRunDate(now());
        $this->site->update([
            'status' => $response->getStatusCode(),
            'next_run' => $nextRun
        ]);
    }

    public function failed($e)
    {
      if ($e instanceof BadResponseException) {
        $response = $e->getResponse();
        $next_run = $this->schedule->getNextRunDate(now());
        $this->site->update([
          "status" => $response->getStatusCode(),
          "next_run" => $next_run,
        ]);
  
        if (empty($this->site->emailed_at) || $this->site->canSendEmail()) {
          $this->site->update(["emailed_at" => now()->toDateTime()]);
          $this->sendNotification(new SiteDown($this->site));
        }
      }
  
      dd($e);
    }

    protected function verifyTextOnResponse($response): bool
    {
        return $this->site->hasCheckString() &&
        !$this->site->validateResponse($response->getBody()) &&
        $this->site->canSendEmail();
    }

    protected function sendNotification($notification)
    {
        $this->site->owner->notify($notification->delay(now()->addSeconds(10)));
    }

    private function verifyHeadersOnResponse(ResponseInterface $response)
    {
        if (empty($this->site->headers)) {
            return true;
        }

        dd(array_diff($this->site->headers, $response->getHeaders()));
    }

    protected function isSiteRecovered(ResponseInterface $response): bool
    {
        return $this->site->isDown() && $this->successfulResponse($response->getStatusCode());
    }

    protected function successfulResponse($response): bool
    {
        return preg_match("/2\d{2}/", $response);
    }

    protected function isChangeInPerformance(): bool
    {
        $latestStats = $this->site->stats()->get();
     
        if($latestStats->count() === 0)
        {
            return false;
        }

        $current = $latestStats[0];
        $previous = $latestStats[1];
        return $current->duration - $previous->duration > 200;
    }

    protected function performChecks($response)
    {
        if ($this->verifyTextOnResponse($response) && $this->verifyHeadersOnResponse($response)) {
            $this->site->update(["emailed_at" => now()->toDateTime()]);
            $this->sendNotification(new MalformedUptimeResponse($this->site, $response->getBody()));
            return;
        }
    
        if ($this->isSiteRecovered($response)) {
            $when = now()->addMinute();
            $this->sendNotification((new SiteRecovered($this->site))->delay($when));
            return;
        }
    
        if ($this->isChangeInPerformance()) {
            $when = now()->addMinute();
            $this->sendNotification((new ChangeInPerformance($this->site))->delay($when));
            return;
        }
    }
}
