<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SslCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
      "site_id",
      "extensions",
      "purposes",
      "signatureTypeSN",
      "signatureTypeLN",
      "serialNumber",
      "serialNumberHex",
      "version",
      "hash",
      "subject",
      "name",
      "issuer",
      "validTo",
      "validFrom",
      "signatureTypeNID",
    ];
    
    protected $dates = ['validTo', 'validFrom'];

    protected $casts = [
      "extensions" => "array",
      "purposes" => "array",
      "issuer" => "array",
    ];
  
    public function site()
    {
      return $this->belongsTo(Site::class, "site_id");
    }

    public function scopeAboutToExpire(Builder $query)
    {
      $query->whereRaw('validTo <= ADDDATE(NOW(), INTERVAL expires DAY)');
    }
}
