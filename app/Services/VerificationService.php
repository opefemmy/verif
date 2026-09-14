<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\VerificationLog;
use Illuminate\Support\Facades\Request;

class VerificationService
{
    /**
     * Verify a certificate using its token.
     *
     * @param string $token
     * @return array Containing result status and the certificate (if found).
     */
    public function verifyToken(string $token): array
    {
        $certificate = Certificate::where('verification_token', $token)->first();

        $result = 'NOT_FOUND';
        if ($certificate) {
            $result = $certificate->status;
        }

        // Log the verification request
        VerificationLog::create([
            'certificate_id' => $certificate?->id,
            'verification_token' => $token,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'result' => $result,
        ]);

        return [
            'status' => $result,
            'certificate' => $certificate,
        ];
    }
}