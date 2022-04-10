<?php

namespace App\Jobs;

use App\Models\SslCertificate;
use App\Notifications\SslCertificateExpiration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SslCertificateWatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $certificates = SslCertificate::aboutToExpire()->get();
        
        foreach($certificates as $certificate)
        {
            $certificate->site->owner->notify(
                (new SslCertificateExpiration($certificate->site))->delay(now()->addSeconds(10))
            );
        }
    }
}
