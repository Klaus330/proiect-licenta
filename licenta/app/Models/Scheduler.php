<?php

namespace App\Models;

use App\Enums\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Site;
use Illuminate\Support\Collection;

class Scheduler extends Model
{
    use HasFactory;
    
    public const SUCCESS_STATUS = 200;
    public const PENDING_STATUS = null;

    public const TIME_BETWEEN_EMAILS = 30;
    public const TYPE_CRON_EXPRESSION = 'cron';
    public const TYPE_INTERVAL = 'interval';
    protected const OVERDUE_LIMIT = '5';

    protected $fillable = [
      "name",
      "method",
      "endpoint",
      "alerts",
      "failure_number",
      "cronExpression",
      "next_run",
      "period",
      'site_id',
      'emailed_at',
      'needs_auth',
      'auth_payload',
      'payload',
      'auth_route',
      'jwt',
      'jwt_expire_date',
      'has_remote_code',
    ];

    public $casts = [
      'auth_payload' => 'array',
      'payload' => 'array',
      'emailed_at' => 'datetime',
    ]; 

    public $timestamps = [ 'emailed_at' ];
  
    public function host()
    {
      return $this->belongsTo(Site::class, "site_id");
    }
  
    public function stats()
    {
      return $this->hasMany(SchedulerStats::class, "scheduler_id");
    }
  
    public function owner()
    {
      return $this->host->owner();
    }
  
    public function isActive()
    {
      return $this->status === self::SUCCESS_STATUS;
    }
  
    public function isPending()
    {
      return $this->status === self::PENDING_STATUS;
    }
  
    public function latestStats()
    {
      return $this->stats()
        ->latest()
        ->first();
    }
  
    public function getUrl(): string
    {
      return "{$this->host->url}/{$this->endpoint}";
    }
  
    public function getStatus()
    {
      if (empty($this->latestStats()->status_code)) {
        return State::PENDING;
      }
  
      return $this->successful() ? State::SUCCESS : State::ERROR;
    }

    public function successful(): bool
    {
      return preg_match("/2\d{2}/", $this->latestStats()->status_code);
    }
  
    public function belongsToSite(Site $site)
    {
      return $site->id === $this->host->id;
    }
  
    public function isOwner($user)
    {
      return $user->id === $this->owner->id;
    }

    public function canSendNotification()
    {
      return $this->emailed_at === null || ($this->emailed_at != null && $this->emailed_at->diff(now())->i > self::TIME_BETWEEN_EMAILS);
    }

    public function scopeLastStatsIsOverdue($query) // TODO: Refactor this to match any cron expression
    {
      $limit = self::OVERDUE_LIMIT;
      $query->whereRaw("DATE_SUB(next_run, INTERVAL {$limit} MINUTE) > (SELECT ended_at from scheduler_stats where scheduler_id = schedulers.id order by ended_at limit 1)");
    }

    public function getAuthPayloadAttribute($value)
    {
      if(is_null($value)) {
        return [];
      }

      return json_decode($value, TRUE);
    }

    public function isJWTTokenExpired()
    {
      if(is_null($this->jwt_expire_date)) {
        return false;
      }

      return $this->jwt_expire_date < now();
    }

    public function remote_code_file()
    {
      return $this->hasOne(RemoteCode::class, 'scheduler_id');
    }

    public function getRemoteCodePathWithFileNameAttribute()
    {
      return $this->remote_code_path . $this->remote_code_file->filename;
    }

    public function getRemoteCodePathAttribute()
    {
      return base_path() . $this->remote_code_path_without_base_path;
    }

    public function getRemoteCodePathWithoutBasePathAttribute()
    {
      return '/public/remote-code/users/' . $this->owner->id . '/' . $this->id . '/';
    }
    
    public function getRemoteCodeOutputPathAttribute()
    {
      return base_path() . $this->remote_code_path . 'output.txt';
    }

    public function getHasRemoteCodeAttribute($value)
    {
      return $this->remote_code_file != null && $value;
    }
}
