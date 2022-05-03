<?php

namespace App\Http\Livewire;

use Livewire\Component;
use League\Csv\Writer;
use File;

class PerformanceChart extends Component
{
    public $interval = 'hourly';

    public $site;

    public $data;

    public $stats;

    public function mount(){
        $this->switchChartInfo($this->interval);
    }

    public function render()
    {
        return view('livewire.performance-chart');
    }

    public function extractDataFromStats(){
        $stats = $this->stats;
        if($stats->isNotEmpty()){
            $created_at = $this->getDates();

            $this->data = [
                'dates' => $created_at,
                'dns_lookup' => $stats->map(function ($stat) {return $stat->dns_lookup / 1000;})->reverse()->values()->toArray(),
                'content_download' => $stats->map(function ($stat) {return ($stat->total_time - $stat->start_transfer_time) / 1000;})->reverse()->values()->toArray(),
                'tls_time' => $stats->map(function ($stat) {return ($stat->appconnect_time * 1000000 - $stat->dns_lookup ) / 1000;})->reverse()->values()->toArray(),
                'transfer_time' => $stats->map(function ($stat) {return ($stat->start_transfer_time - $stat->appconnect_time * 1000000 ) / 1000;})->reverse()->values()->toArray(),
                'total_time' =>  $stats->map(function ($stat) {return $stat->total_time / 1000;})->reverse()->values()->toArray(),
            ];
        }else{
            $this->data = [];
        }
    }    

    public function updatedInterval($value)
    {
        $this->switchChartInfo($value);
        $this->dispatchBrowserEvent('updatechart');
    }


    public function getHourlyStats()
    {
        return $this->statsFor(now()->subHour())->get();
    }

    public function getDailyStats()
    {
        return $this->statsFor(now()->subDay())->where('created_at', '<', now()->addDay())->get();
    }

    public function getWeeklyStats()
    {
        return $this->statsFor(now()->subWeek())->get();
    }

    public function statsFor($condition)
    {
        return $this->site
                    ->stats()
                    ->where('created_at', '>=', $condition);
    }

    public function switchChartInfo($chartInfo)
    {
        switch($chartInfo){
            case 'hourly':
                $this->stats = $this->getHourlyStats();
                break;
            case 'daily':
                $this->stats = $this->getDailyStats()->filter(function($item){
                    return $item->created_at->minute === 0;
                })->values()->reverse();
                break;
            case 'weekly':
                $this->stats = $this->getWeeklyStats()->groupBy(function($item) {
                                    return $item->created_at->format('d'); // grouping by day
                                })->map(function($collection){
                                    return $collection->filter(function($item){
                                        return $item->created_at->minute === 0;
                                    })->values();
                                })->flatten()->values();
                break;
        }

        $this->extractDataFromStats();
    }


    public function getDates()
    {
        switch($this->interval){
            case 'hourly':
                $created_at = $this->stats->pluck('created_at')->toArray();
                $created_at = array_values(array_filter($created_at, function($created_at) {
                    return $created_at->minute  % 2 == 0;
                }));

                $created_at = array_map(function($created_at) {
                    return $created_at->format("H:i");
                }, $created_at);
                return $created_at;
            break;

            case 'daily':
                return $this->stats->pluck('created_at')->map(function($created_at) {
                    return $created_at->format("D H:i");
                })->toArray();

                break;

            case 'weekly':
                return $this->stats->map(function($item){
                                        return $item->created_at->format('D');
                                    })->toArray();
                break;
        }
    }
}
