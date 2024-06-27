<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'short_name',
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'spec');
    }
}
