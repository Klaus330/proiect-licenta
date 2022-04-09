<?php

namespace App\Repositories;

use App\Models\SiteStats;

class SiteStatsRepository 
{
    public function create($attributes)
    {
        return SiteStats::create($attributes);    
    }
}