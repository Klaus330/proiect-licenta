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

    public function updateSettings($website, $attributes)
    {
      $newUrl = $attributes["schema"] . "://" . $attributes["host"];
      if (array_key_exists("name", $attributes)) {
        $nameTaken =
          Site::where("name", $attributes["name"])
            ->where("id", "<>", $website->id)
            ->exists();
            
        if ($nameTaken) {
          return false;
        }
      }
  
    //   $headers = null;
    //   if (array_key_exists("field-names", $attributes) && array_key_exists("field-values", $attributes)) {
    //     $headers = array_filter(array_combine($attributes["field-names"], $attributes["field-values"]));
    //   }
  
      return $website->update([
        "url" => $newUrl,
        "name" => $attributes["name"],
        // "headers" => $headers,
      ]);
    }
}