<?php

namespace App\Repositories;

use App\Exceptions\SiteDuplication;
use App\Models\Site;
use Cron\CronExpression;


class SiteRepository
{
    public function createOrFail($data)
    {
        $url = parse_url($data['url']);
        
        if($this->existsByUrl($url['host']))
        {
            throw new SiteDuplication('You are already monitoring this site');
            return;
        }

        $data['user_id'] =  auth()->user()->id;
        $data['next_run'] = (new CronExpression("* * * * *"))->getNextRunDate(now());

        return Site::create($data);
    }

    public function existsByUrl($url)
    {
        return Site::where('user_id', auth()->user()->id)->where('url', 'like', "%{$url}%")->get()->count() > 0;
    }
}