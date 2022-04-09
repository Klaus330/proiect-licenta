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
    ];


    public function getStatus()
    {   
        if($this->status === self::PENDING_STATE)
        {
            return self::PENDING_STATE;
        }

        return boolval(preg_match("/2\d{2}/", $this->status));
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
  
}
