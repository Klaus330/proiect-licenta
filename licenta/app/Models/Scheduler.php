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
      return $this->host->owner;
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
      return $this->emailed_at != null && $this->emailed_at->dffInMinutes() > 30;
    }

    public function scopeLastStatsIsOverdue($query) // TODO: Refactor this to match any cron expression
    {
      $limit = self::OVERDUE_LIMIT;
      $query->whereRaw("DATE_SUB(next_run, INTERVAL {$limit} MINUTE) > (SELECT ended_at from scheduler_stats where scheduler_id = schedulers.id order by ended_at limit 1)");
    }
}
