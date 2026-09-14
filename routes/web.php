<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\InstitutionSettingController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\VerificationLogController;
use App\Http\Controllers\VerificationController;
use App\Models\InstitutionSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $settings = InstitutionSetting::first();
    return view('home', compact('settings'));
});

Route::get('/verify/{token}', [VerificationController::class, 'show'])
    ->middleware('throttle:60,1') // 60 requests per minute
    ->name('verify');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Area
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            $stats = [
                'total' => \App\Models\Certificate::count(),
                'valid' => \App\Models\Certificate::where('status', 'VALID')->count(),
                'revoked' => \App\Models\Certificate::where('status', 'REVOKED')->count(),
                'verifications' => \App\Models\VerificationLog::count(),
            ];
            $recentCertificates = \App\Models\Certificate::latest()->take(5)->get();
            $recentVerifications = \App\Models\VerificationLog::latest()->take(5)->get();

            return view('admin.dashboard', compact('stats', 'recentCertificates', 'recentVerifications'));
        })->name('dashboard');

        Route::get('/settings', [InstitutionSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [InstitutionSettingController::class, 'update'])->name('settings.update');

        Route::post('certificates/bulk-delete', [CertificateController::class, 'bulkDelete'])->name('certificates.bulk-delete');
        Route::resource('certificates', CertificateController::class);
        Route::post('certificates/{certificate}/revoke', [CertificateController::class, 'revoke'])->name('certificates.revoke');
        Route::post('certificates/{certificate}/restore', [CertificateController::class, 'restore'])->name('certificates.restore');
        Route::get('certificates/{certificate}/download-qr', [CertificateController::class, 'downloadQr'])->name('certificates.download-qr');
        Route::post('certificates/{certificate}/regenerate-qr', [CertificateController::class, 'regenerateQr'])->name('certificates.regenerate-qr');

        Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('qr-codes.index');
        Route::get('/qr-codes/download-all', [QrCodeController::class, 'downloadAll'])->name('qr-codes.download-all');
        Route::post('/qr-codes/download-filtered', [QrCodeController::class, 'downloadFiltered'])->name('qr-codes.download-filtered');
        Route::post('/qr-codes/download-selected', [QrCodeController::class, 'downloadSelected'])->name('qr-codes.download-selected');

        Route::get('/import', [ImportController::class, 'index'])->name('import.index');
        Route::get('/import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');
        Route::match(['get', 'post'], '/import/preview', [ImportController::class, 'preview'])->name('import.preview');
        Route::get('/import/photo', [ImportController::class, 'showPreviewPhoto'])->name('import.photo');
        Route::post('/import/confirm', [ImportController::class, 'confirm'])->name('import.confirm');
        Route::get('/verification-logs', [VerificationLogController::class, 'index'])->name('verification-logs.index');
    });
});

require __DIR__.'/auth.php';