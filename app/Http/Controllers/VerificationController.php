<?php

namespace App\Http\Controllers;

use App\Services\VerificationService;
use App\Models\InstitutionSetting;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function show($token)
    {
        $verification = $this->verificationService->verifyToken($token);
        $settings = InstitutionSetting::first();

        return view('verify', [
            'verification' => $verification,
            'settings' => $settings,
            'verifiedAt' => now(),
        ]);
    }
}