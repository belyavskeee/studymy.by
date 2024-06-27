<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    // Укажите все столбцы, которые могут быть массово назначены
    protected $fillable = [
        'name',
        'short_name',
        'teacher1',
        'teacher2',
        'spec',
        'group_id',
        'hours',
        'hours_spent',
        'semester',
        'period_start',
        'period_end',
    ];

    // Определите отношения, если они имеются
    public function teacher1()
    {
        return $this->belongsTo(User::class, 'teacher1');
    }

    public function teacher2()
    {
        return $this->belongsTo(User::class, 'teacher2');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'spec');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
