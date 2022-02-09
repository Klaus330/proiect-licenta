<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    
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
        return null;
    }

    public function hasCronMonitors()
    {
        return false;
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
      return $this->status === "pending";
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
        return strtolower($this->schema) === "https";
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

}
