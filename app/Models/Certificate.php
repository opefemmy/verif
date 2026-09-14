<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use SoftDeletes;

    protected $casts = [
        'graduation_date' => 'date',
    ];

    protected $fillable = [
        'full_name',
        'birth_name',
        'matric_number',
        'passport_photo',
        'school',
        'faculty',
        'department',
        'programme',
        'qualification',
        'award',
        'class_of_award',
        'graduation_date',
        'graduation_year',
        'academic_session',
        'certificate_number',
        'verification_token',
        'status',
        'remarks',
    ];
}