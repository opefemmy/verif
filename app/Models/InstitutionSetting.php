<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionSetting extends Model
{
    protected $fillable = [
        'institution_name',
        'short_name',
        'motto',
        'logo',
        'address',
        'city',
        'state',
        'country',
        'website',
        'official_email',
        'phone',
        'verification_title',
        'verification_footer',
        'favicon',
    ];
}
