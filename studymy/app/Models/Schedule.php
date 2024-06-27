<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'number_sub', 'group_id', 'time', 'subject_id', 'lecture_id', 'type', 'class_1', 'class_2', 'teacher_one', 'teacher_two'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function teacher1()
    {
        return $this->belongsTo(User::class, 'teacher_one');
    }

    public function teacher2()
    {
        return $this->belongsTo(User::class, 'teacher_two');
    }
}
