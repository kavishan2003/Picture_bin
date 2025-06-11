<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Images extends Model
{
    protected $fillable = [
        'id',
        'image_path',
        'original_name',
        'size',
    ];
}
