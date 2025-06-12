<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadAttempt extends Model
{
    protected $table = 'upload_attempts';

    protected $fillable = [
        'ip_address',
        'upload_count',
        'date',
    ];

    public $timestamps = true;

    protected $casts = [
        'date' => 'date',
    ];
}
