<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    
    protected const SECURE_HTTP = "https";
    protected const PENDING_STATE = 'pending';
    protected const VERB_GET = 'GET';
    protected const VERB_POST = 'POST';
    protected const OVERDUE_LIMIT = '5';

    public $fillable = [
        "url",
        "user_id",
        "ssl",
        "status",
        "next_run",
        "emailed_at",
        "verb",
        "payload",
        "check",
        "timeout",
        "downtime",
        "name",
        "headers",
    ];

    public $casts = [
        "payload" => "array",
        "headers" => "array",
    ];


    public function getStatus()
    {   
        if($this->status === self::PENDING_STATE)
        {
            return self::PENDING_STATE;
        }

        return boolval(preg_match("/2\d{2}/", $this->status));
    }

    public function getHeadersAttribute($value)
    {
        if($value === null)
        {
            return $value;
        }

        return (array) json_decode($value);
    }

    public function schedulers()
    {
        return $this->hasMany(Scheduler::class);
    }

    public function hasSchedulers()
    {
        return $this->schedulers()->count() > 0;
    }

    public function getHost()
    {
        preg_match_all(
            "/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?)/",
            $this->url,
            $matches
        );
        
        return count($matches[2]) > 0 ? $matches[2][0] : $this->url;
    }

    public function isUp(): bool
    {
        return preg_match("/2\d{2}/", $this->status);
    }

    public function isDown(): bool
    {
        return !$this->isUp();
    }

    public function isPending()
    {
      return $this->status === self::PENDING_STATE;
    }

    public function getSslCertificateStatus()
    {
        if (!$this->hasSslCertificate()) {
            return null;
        }

        return $this->sslCertificate->validTo->gt(now());
    }

    public function hasSslCertificate()
    {
        return $this->sslCertificate()->exists();
    }

    public function sslCertificate()
    {
        return $this->hasOne(SslCertificate::class, "site_id");
    }

    public function stats()
    {
      return $this->hasMany(SiteStats::class, "site_id")->latest();
    }

    public function isSecured()
    {
        return strtolower($this->schema) === self::SECURE_HTTP;
    }
    
    public function getHostAttribute()
    {
      $parsedUrl = parse_url($this->url);
      return $parsedUrl["host"];
    }
    
    public function getSchemaAttribute()
    {
        $parsedUrl = parse_url($this->url);
        return $parsedUrl["scheme"];
    }

    public function getNameAttribute($value)
    {
        if (empty($value)) {
            return $this->url;
        }

        return $value;
    }

    public function owner()
    {
      return $this->belongsTo(User::class, "user_id");
    }  

    public function isOwner($user)
    {
      return $this->owner->id === $user->id;
    }  

    public function getUrlAttribute($value)
    {
        return trim($value, '/');
    }

    public function acceptsGet()
    {
        return $this->method === self::VERB_GET;
    }

    public function hasCheckString()
    {
      return !empty($this->check);
    }

    public function validateResponse($responseBody)
    {
      return $this->check === $responseBody;
    }
    
    public function allowedToSendEmail()
    {
      return now()->diffInHours($this->emailed_at) > 1;
    }

    public function hasTimeout()
    {
        return $this->timeout != 0;
    }

    public function scopeLastStatsOverdue($query)
    {
        $limit = self::OVERDUE_LIMIT;
        $query->whereRaw("DATE_SUB(next_run, INTERVAL {$limit} MINUTE) > (SELECT ended_at from site_stats where site_id = sites.id order by ended_at limit 1)");
    }

    public function getLastMonthMonitoringInfo()
    {
        $latestStats = $this->stats->groupBy(function($item) {
            return $item->created_at->format('d');
        })->map(function($collection){
            return $collection->first();
        })->flatten()->take(30);

        $array = [];
        foreach($latestStats as $stats)
        {
            $array[now()->day - $stats->ended_at->day] = $stats;
        }
        $latestStats = $array;

        if(! array_key_exists(0, $latestStats) && array_key_exists(1, $latestStats))
        {
            $latestStats[0] = $latestStats[1];
        }

        return $latestStats;
    }

    public function getLastIncidentAttribute()
    {
        return $this->stats()
                    ->where('http_code', 'not like', '2__')
                    ->where('http_code', 'not like', '3__')
                    ->first();
    }
}
