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
    
    protected $timeout = 30;

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
            // event(new SslVerificationFailed($site));
            return Command::FAILURE;
        }

        $site = Site::find(["id" => $siteId])->first();

        $this->info("Check if webiste is up");
        $responseStatus = Http::withOptions(["verify"=>false])->get($site->getHost());

        if (!$responseStatus->successful()) {
            $this->error("Http request failed. Code:" . $responseStatus);
            // event(new SslVerificationFailed($site));
            return Command::FAILURE;
        }

        $this->info("Check if webiste is has certificate registered");
        $hasCertificateRegistered = $site->hasSslCertificate();
    
        $this->info("Getting ips for domain");

        $ip = $this->getBindingIp($site->getHost());

        $this->info("Getting certificate for ip");
        $cert = $this->getCertificate($site->getHost());
        if($cert === false)
        {
            // event(new SslVerificationFailed($site));
            return Command::FAILURE;
        }

        $sslInfo = $this->getSslInfo($cert);
        
        if($cert === false)
        {
            // event(new SslVerificationFailed($site));
            return Command::FAILURE;
        }

        $this->info("Add ssl certificate info in db.");

        if($hasCertificateRegistered){
            $this->sslRepo->update(
                $site->sslCertificate->id,
                $this->retrieveAttributes($sslInfo, $site, $ip)
            );
        }else{
            $this->sslRepo->create(
                $this->retrieveAttributes($sslInfo, $site, $ip)
            );
        }

        $this->info("Finish gathering information.");

        return Command::SUCCESS;
    }

    protected function getSslInfo($cert){
        try{
            if (! is_resource($cert) || get_resource_type($cert) !== 'stream' ) {
                return false;
            }

            $context = stream_context_get_params($cert);

            $sslInfo = openssl_x509_parse(
                $this->getCertificateInfoFromContext($context)
            );
            
            return $sslInfo;
        }catch(\Exception $e){
            return false;
        }
    }

    protected function getBindingIp($url)
    {
        $records = dns_get_record($url, DNS_A + DNS_AAAA);
        $ips = [];

        foreach ($records as $record) {
            if(isset($record['ip'])){
                $ips[] = $record['ip'];
            }
    
            if(isset($record['ipv6'])){
                $ips[] = $record['ip'];
            }
        }
        
        return $ips[0] ?? null;
    }


    protected function getCertificateinfoFromContext($context)
    {
        return $context['options']['ssl']['peer_certificate'];
    }

    protected function getCertificate($url)
    {
        
        try{
            $cert = stream_socket_client(
                "ssl://{$url}:443", 
                $errno, 
                $errorMessage, 
                $this->timeout, 
                STREAM_CLIENT_CONNECT, 
                $this->getStreamContext()
            );
        }catch(\Exception $e){
            return false;
        }

        return $cert;
    }

    protected function getStreamContext()
    {
        return stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                "capture_peer_cert" => true
            ],
        ]);
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
            'updated_at' => now()
        ];
    }
}
