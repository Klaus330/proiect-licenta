<?php

namespace App\Console\Commands;

use App\Lighthouse\LighthouseAuditor;
use App\Models\Site;
use Dzava\Lighthouse\Lighthouse;
use Illuminate\Console\Command;

class LighthouseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lighthouse {site}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $site = $this->argument("site");
  
        if($site == null)
        {
            $this->error("Site not found");
            return Command::FAILURE;
        }

        $this->info("Starting Lighthouse audit...");
        
        $outputPath = "./public/lighthouse/{$site->id}/";
        if (!is_dir($outputPath)) {
            mkdir($outputPath);
        }

        (new LighthouseAuditor())->accessibility()
        ->bestPractices()
        ->performance()
        ->pwa()
        ->seo()
        ->outputFormat('html')
        ->outputPath($outputPath . 'report.html')
        ->audit($site);

        $this->info("Lighthouse audit finished.");

        return Command::SUCCESS;
    }
}
