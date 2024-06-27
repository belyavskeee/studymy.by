<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'user_id', 'files', 'device_info_user'];

    protected $casts = [
        'files' => 'array',
    ];
}
