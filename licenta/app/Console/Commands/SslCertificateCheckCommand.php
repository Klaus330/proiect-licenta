<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use App\Repositories\SslRepository;
use App\Events\SslVerificationFailed;
use Illuminate\Support\Facades\Http;

class SslCertificateCheckCommand extends Command
{
    protected $site;
    protected $sslRepo;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "ssl:check {site}";
  
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Verifies the ssl cerfiticate for a given site";
  
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Site $site, SslRepository $sslRepository)
    {
      parent::__construct();
      $this->site = $site;
      $this->sslRepo = $sslRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $siteId = $this->argument("site");

        if (!$siteId) {
            $this->info("You need to provide a site");

            return Command::FAILURE;
        }

        $site = Site::find(["id" => $siteId])->first();
    
        $this->info("Check if webiste is up");
        $responseStatus = Http::get($site->getHost());

        if (!$responseStatus->successful()) {
            $this->error("Http request failed. Code:" . $responseStatus);
            return Command::FAILURE;
        }
    
        $this->info("Getting ips for domain");
    
        $ips = $this->getIps($site->getHost());
    
        $this->info("Getting certificate for ip");
    
        try {
            $certificate = $this->getCert($ips[0], $site->getHost());
        } catch (\Exception $e) {
            // event(new SslVerificationFailed($site));

            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    
        $this->sslRepo->create($this->retrieveAttributes($certificate, $site, $ips[0]));
        $site->update(["ssl" => 1]);
        $this->info("Site ssl certificate verified");
    
        return Command::SUCCESS;
    }

    function getIps($domain) // TODO: TO REFACTOR
    {
        $ips = [];
        $dnsRecords = dns_get_record($domain, DNS_A + DNS_AAAA);
        foreach ($dnsRecords as $record) {
        if (isset($record["ip"])) {
            $ips[] = $record["ip"];
        }
        if (isset($record["ipv6"])) {
            $ips[] = "[" . $record["ipv6"] . "]"; // bindto of 'stream_context_create' uses this format of ipv6
        }
        }
        return $ips;
    }

    function getCert($ip, $domain)
    {
        $g = stream_context_create(["ssl" => ["capture_peer_cert" => true], "socket" => ["bindto" => $ip]]);
        $r = stream_socket_client("ssl://{$domain}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $g);
        $cont = stream_context_get_params($r);
        return openssl_x509_parse($cont["options"]["ssl"]["peer_certificate"]);
    }

    protected function retrieveAttributes($certificate, $site, $ip)
    {
        return [
            "site_id" => $site->id,
            "name" => $certificate["name"],
            "subject" => $certificate["subject"]['CN'],
            "hash" => $certificate["hash"],
            "issuer" => $certificate["issuer"]['CN'],
            "version" => $certificate["version"],
            "serialNumber" => $certificate["serialNumber"],
            "serialNumberHex" => $certificate["serialNumberHex"],
            "signatureTypeSN" => $certificate["signatureTypeSN"],
            "signatureTypeLN" => $certificate["signatureTypeLN"],
            "signatureTypeNID" => $certificate["signatureTypeNID"],
            "purposes" => json_encode($certificate["purposes"]),
            "extensions" => json_encode($certificate["extensions"]),
            "validTo" => (new \Carbon\Carbon($certificate["validTo_time_t"]))->toDateTime(),
            "validFrom" => (new \Carbon\Carbon($certificate["validFrom_time_t"]))->toDateTime(),
            'ipAddress' => $ip,
        ];
    }
}
