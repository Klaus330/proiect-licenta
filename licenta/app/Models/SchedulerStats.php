<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulerStats extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['executed_at'];

    public function getHeadersAttribute($value)
    {
      return json_decode($value);
    }

    public function getDurationAttribute($value)
    {
      return floor($value * 1000);
    }

    public function successful(): bool
    {
      return preg_match("/2\d{2}/", $this->status_code);
    }
}
