<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    protected $fillable = [
        'certificate_id',
        'verification_token',
        'ip_address',
        'user_agent',
        'result',
    ];

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}
