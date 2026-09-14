<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;

class QrCodeService
{
    /**
     * Generate a cryptographically secure unique verification token.
     */
    public function generateToken(): string
    {
        return Str::random(16);
    }

    /**
     * Generate a QR code for a certificate and save it as a PNG.
     *
     * @param string $token The verification token.
     * @param string $matricNumber The student's matriculation number.
     * @return string The path to the saved QR code.
     */
    public function generateQrCode(string $token, string $matricNumber): string
    {
        // 1. Construct the verification URL
        $url = route('verify', ['token' => $token]);

        // 2. Sanitize matric number for filename
        $filename = $this->sanitizeMatricNumber($matricNumber) . '.png';
        $path = 'qr-codes/' . $filename;

        try {
            // Attempt standard PNG generation (requires Imagick)
            $image = QrCodeFacade::format('png')
                ->size(300)
                ->errorCorrection('H')
                ->margin(2)
                ->generate($url);

            Storage::disk('public')->put($path, $image);
            return $path;
        } catch (\Exception $e) {
            // FALLBACK: Use a reliable external API to generate the PNG and save it locally.
            // This ensures the user gets a PNG file even if Imagick is not installed on the server.
            try {
                $apiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);
                $imageContent = file_get_contents($apiUrl);

                if ($imageContent === false) {
                    throw new \Exception('Could not fetch QR code from fallback API.');
                }

                Storage::disk('public')->put($path, $imageContent);
                return $path;
            } catch (\Exception $apiEx) {
                // Final Fallback: SVG (which never fails)
                $svgFilename = $this->sanitizeMatricNumber($matricNumber) . '.svg';
                $svgPath = 'qr-codes/' . $svgFilename;

                $image = QrCodeFacade::format('svg')
                    ->size(300)
                    ->errorCorrection('H')
                    ->margin(2)
                    ->generate($url);

                Storage::disk('public')->put($svgPath, $image);
                return $svgPath;
            }
        }
    }

    /**
     * Sanitize matriculation number to be a safe filename.
     * Replaces slashes and non-alphanumeric characters with underscores.
     */
    public function sanitizeMatricNumber(string $matricNumber): string
    {
        // Replace any character that is not a letter or digit with an underscore
        // This turns HND/CS/2024/0002 into HND_CS_2024_0002
        return preg_replace('/[^A-Za-z0-9]/', '_', $matricNumber);
    }

    /**
     * Regenerate QR code for an existing token.
     */
    public function regenerateQrCode(string $token, string $matricNumber): string
    {
        return $this->generateQrCode($token, $matricNumber);
    }
}