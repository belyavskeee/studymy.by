<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_group',
        'kyrs',
        'speciality_id',
        'audience',
        'type_education',
        'after_school',
        'year_z',
        'year_ot',
        'otchisl',
        'delete_group',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'speciality_id');
    }
}
