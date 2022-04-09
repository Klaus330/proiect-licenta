<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteStats extends Model
{
    use HasFactory;

    
  protected $fillable = [
    "site_id",
    "dns_lookup",
    "total_time",
    "connect_time",
    "speed_download",
    "size_download",
    "header_size",
    "request_size",
    "content_type",
    "content_length",
    "primary_port",
    "appconnect_time",
    "start_transfer_time",
    "http_code",
    "pretransfer_time",
    "redirect_count",
    "redirect_time",
    "server",
    "date",
    "connection",
    "protocol_version",
    "http_version",
    "scheme",
    "headers",
    "reason_phrase",
    "user_agent",
    "started_at",
    "ended_at",
    "duration",
  ];

  protected $casts = [
    "headers" => "array",
  ];

  protected $dates = ['ended_at', 'started_at'];

  protected $table = "site_stats";

  public function getDurationAttribute($seconds)
  {
    if ($seconds < 1) {
      $milisec = ceil($seconds * 1000);
      return $milisec;
    } else {
      $duration = ceil($seconds);
      return $duration;
    }
  }

  public function getFormatedDuration()
  {
    if ($this->duration < 1000) {
      return "{$this->duration} ms";
    } else {
      $duration = $this->duration / 1000;
      return "{$duration} sec";
    }
  }

  public function successful()
  {
    return preg_match("/2\d{2}/", $this->http_code);
  }
}
