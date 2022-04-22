<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Site;
class SiteRoute extends Model
{
    use HasFactory;

    protected $fillable = ['site_id', 'route', 'http_code', 'found_on'];

    public function host()
    {
        return $this->belongsTo(Site::class);
    }

    public function isUp(): bool
    {
        return preg_match("/2\d{2}/", $this->http_code);
    }

    public function isBroken(): bool
    {
        return !$this->isUp();
    }
}
