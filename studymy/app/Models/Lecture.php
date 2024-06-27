<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'timetable_id',
        'type',
        'teacher_1_id',
        'teacher_2_id',
        'date',
        'theme',
        'hours',
        'homework',
        'OKR',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function teacher1()
    {
        return $this->belongsTo(User::class, 'teacher_1_id');
    }

    public function teacher2()
    {
        return $this->belongsTo(User::class, 'teacher_2_id');
    }
}

