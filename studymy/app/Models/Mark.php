<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'lecture_id',
        'teacher_id',
        'student_id',
        'type',
        'hour',
        'mark'
    ];

    // Отношения с другими моделями
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
