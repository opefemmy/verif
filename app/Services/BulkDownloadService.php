<?php

namespace App\Services;

use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Str;

class BulkDownloadService
{
    /**
     * Download multiple QR codes as a ZIP file.
     *
     * @param iterable $certificates Collection of certificates to include.
     * @param string $zipName The name of the resulting zip file.
     * @return string Path to the created zip file.
     */
    public function downloadQrCodes(iterable $certificates, string $zipName = 'Certificate-QR-Codes'): string
    {
        $zipFileName = $zipName . '_' . now()->timestamp . '.zip';
        $zipPath = Storage::disk('public')->path($zipFileName);
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($certificates as $certificate) {
                // Use the same sanitization logic as QrCodeService (replace non-alphanumeric with _)
                $sanitized = preg_replace('/[^A-Za-z0-9]/', '_', $certificate->matric_number);

                // Try PNG first, then SVG
                $pngFilename = $sanitized . '.png';
                $svgFilename = $sanitized . '.svg';
                $pngPath = Storage::disk('public')->path('qr-codes/' . $pngFilename);
                $svgPath = Storage::disk('public')->path('qr-codes/' . $svgFilename);

                if (file_exists($pngPath)) {
                    $zip->addFile($pngPath, 'qr-codes/' . $pngFilename);
                } elseif (file_exists($svgPath)) {
                    $zip->addFile($svgPath, 'qr-codes/' . $svgFilename);
                } else {
                    \Log::warning("QR code missing for certificate ID {$certificate->id}: {$sanitized}");
                }
            }

            if (!$zip->close()) {
                throw new \Exception('Failed to finalize ZIP file.');
            }
        } else {
            throw new \Exception('Could not create ZIP file.');
        }

        return $zipFileName;
    }
}