<?php

namespace App\Console\Commands;

use App\Models\Site;
use Illuminate\Console\Command;
use RuntimeException;
use Illuminate\Support\Facades\Http;
use App\Repositories\SslRepository;

class NewSslCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $site = Site::all()->last();

        $this->info("Check if webiste is up");
        $responseStatus = Http::withOptions(["verify"=>false])->get($site->getHost());
        
        if (!$responseStatus->successful()) {
            $this->error("Http request failed. Code:" . $responseStatus);
            return Command::FAILURE;
        }
    
        $this->info("Getting ips for domain");

        $ip = $this->getBindingIp($site->getHost());

        $this->info("Getting certificate for ip");
        $cert = $this->getCertificate($site->getHost());

        if($cert === false)
        {
            return Command::FAILURE;
        }

        $sslInfo = $this->getSslInfo($cert);
        
        if($cert === false)
        {
            return Command::FAILURE;
        }

        $this->info("Add ssl certificate info in db.");
        $this->sslRepo->create(
            $this->retrieveAttributes($sslInfo, $site, $ip)
        );
        $this->info("Finish gathering information.");

        return Command::SUCCESS;
    }

    public function getSslInfo($cert){
        try{
            if (! is_resource($cert) || get_resource_type($cert) !== 'stream' ) {
                throw new RuntimeException('param $cert is not type stream');
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

    public function getBindingIp($url)
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


    public function getCertificateinfoFromContext($context)
    {
        return $context['options']['ssl']['peer_certificate'];
    }

    public function getCertificate($url)
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
            throw new RuntimeException($e->getMessage());
            return false;
        }

        return $cert;
    }

    public function getStreamContext()
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
        ];
    }
}
