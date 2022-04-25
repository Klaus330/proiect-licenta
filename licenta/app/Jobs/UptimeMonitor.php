<?php

namespace App\Jobs;

use App\Exceptions\BadHostProvided;
use App\Models\Site;
use App\Notifications\ChangeInPerformance;
use App\Notifications\MalformedUptimeResponse;
use App\Notifications\SiteDown;
use App\Notifications\SiteRecovered;
use App\Notifications\WebsiteIsSlow;
use App\Notifications\WrongSiteProvided;
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
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class UptimeMonitor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected const MODIFIED_PERFORMANCE_LIMIT = 200;
    protected const LOW_PERFORMANCE_LIMIT = 1200;
    protected const BAD_HOST_PROVIDED_CODE_ERROR = 404;

    public $tries = 3;
    public $backoff = 1;
    public $maxException = 2;
    public $failOnTimeout = true;
    public $timeout = 2;

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
            RequestOptions::HEADERS => (array) $this->site->headers ?? [],
            RequestOptions::FORM_PARAMS => $this->site->acceptsGet() ? [] : $this->site->payload,
            RequestOptions::ON_STATS => function (TransferStats $stats) use ($startedAt, $start) {
                $response = $stats->getResponse();
                $request = $stats->getRequest();

                $endedAt = new \DateTime();
                $end = microtime(true);
                $duration = $end - $start;
                
                if(empty($response))
                {
                    throw new BadHostProvided([
                        'duration' => $duration,
                        'ended_at' => $endedAt,
                        'started_at' => $startedAt,
                    ], 'The host you provided is wrong.');
                }

                $attributes = [
                    "site_id" => $this->site->id,
                    "dns_lookup" => $stats->getHandlerStat("namelookup_time_us"),
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
                    "start_transfer_time" => $stats->getHandlerStat("starttransfer_time_us"),
                    "http_code" => $stats->getHandlerStat("http_code"),
                    "pretransfer_time" => $stats->getHandlerStat("pretransfer_time_us"),
                    "redirect_count" => $stats->getHandlerStat("redirect_count"),
                    "redirect_time" => $stats->getHandlerStat("redirect_time_us"),
                    'connect_time' => $stats->getHandlerStat("connect_time_us"),
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
                    'body' => $response->getBody(),
                    'primary_ip' => $stats->getHandlerStat("primary_ip"),
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
  
        if (empty($this->site->emailed_at) || $this->site->allowedToSendEmail()) {
          $this->site->update(["emailed_at" => now()->toDateTime()]);
          $this->sendNotification(new SiteDown($this->site));
        }
      }else if($e instanceof BadHostProvided)
      {
        $attributes = $e->getExceptionData();
        $attributes['http_code'] = self::BAD_HOST_PROVIDED_CODE_ERROR;

        $next_run = $this->schedule->getNextRunDate(now());
        $this->site->update([
          "status" => self::BAD_HOST_PROVIDED_CODE_ERROR,
          "next_run" => $next_run,
        ]);

        if (empty($this->site->emailed_at) || $this->site->allowedToSendEmail()) {
            $this->site->update(["emailed_at" => now()->toDateTime()]);
            $this->sendNotification(new WrongSiteProvided($this->site));
        }
      }

      var_dump($e);
    }

    protected function verifyTextOnResponse($response): bool
    {
        return $this->site->hasCheckString() &&
        !$this->site->validateResponse($response->getBody()) &&
        $this->site->allowedToSendEmail();
    }

    protected function sendNotification($notification)
    {
        $this->site->owner->notify($notification->delay(now()->addSeconds(10)));
    }

    private function verifyHeadersOnResponse(ResponseInterface $response)
    {
        if (empty($this->site->headers)) {
            return false;
        }
        dd($this->site->headers);
        dd($response->getHeaders());
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
     
        if($latestStats->count() < 2)
        {
            return false;
        }

        $current = $latestStats[0];
        return $current->duration  > $this->site->average_performance / 2;
    }

    protected function isSiteTooSlow()
    {
        $latestStats = $this->site->stats->last();
        return $latestStats->duration > self::LOW_PERFORMANCE_LIMIT;
    }

    protected function performChecks($response)
    {
        if ($this->verifyTextOnResponse($response)) {
            $this->site->update(["emailed_at" => now()->toDateTime()]);
            $this->sendNotification(new MalformedUptimeResponse($this->site, $response->getBody()));
            return;
        }
        
        if($this->verifyHeadersOnResponse($response)){
            info('Headers failed');
            dd('hit');
            // $this->site->update(["emailed_at" => now()->toDateTime()]);
            // $this->sendNotification(new MalformedUptimeResponse($this->site, $response->getBody()));
            return;
        }
    
        if ($this->isSiteRecovered($response)) {
            $when = now()->addMinute();
            $this->sendNotification((new SiteRecovered($this->site))->delay($when));
            return;
        }
    
        if ($this->isChangeInPerformance() && $this->site->allowedToSendEmail()) {
            $when = now()->addMinute();
            $this->sendNotification((new ChangeInPerformance($this->site))->delay($when));
            return;
        }

        if($this->isSiteTooSlow() && $this->site->allowedToSendEmail())
        {
            $this->site->update(["emailed_at" => now()->toDateTime()]);
            $when = now()->addMinute();
            $this->sendNotification((new WebsiteIsSlow($this->site))->delay($when));

            return;
        }
    }
}
