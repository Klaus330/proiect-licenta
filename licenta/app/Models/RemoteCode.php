<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemoteCode extends Model
{
    use HasFactory;

    public $fillable = [
        'path',
        'language',
        'scheduler_id',
        'filename'
    ];

    public $table = 'remote_code';
}
