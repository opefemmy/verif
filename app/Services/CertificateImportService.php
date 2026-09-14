<?php

namespace App\Services;

use App\Models\Certificate;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CertificateImportService
{
    protected $qrService;

    public function __construct(QrCodeService $qrService)
    {
        $this->qrService = $qrService;
    }

    public function previewImport(string $filePath, ?string $zipPath = null): array
    {
        // Use the dedicated Import class to satisfy the library's type requirements
        $data = Excel::toArray(new \App\Imports\CertificatesImport, $filePath);

        if (empty($data) || !isset($data[0])) {
            throw new \Exception('The uploaded file is empty or invalid.');
        }

        $sheet = $data[0];
        $header = array_shift($sheet);

        $preview = [];
        $errors = [];
        $tempExtractPath = null;

        if ($zipPath) {
            $tempExtractPath = storage_path('app/temp-previews-' . uniqid());
            $zipFile = Storage::disk('local')->path($zipPath);
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo($tempExtractPath);
                $zip->close();
            } else {
                // Log error or handle failed ZIP opening
            }
        }

        // Pre-scan for photos if ZIP was provided to avoid repeated scandir in loop
        $availablePhotos = [];
        if ($tempExtractPath && is_dir($tempExtractPath)) {
            try {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($tempExtractPath, \RecursiveDirectoryIterator::SKIP_DOTS)
                );
                foreach ($iterator as $file) {
                    if ($file->isFile() && preg_match('/\.(jpg|jpeg|png)$/i', $file->getFilename())) {
                        // Store relative path from tempExtractPath to the file
                        $relativePath = str_replace($tempExtractPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                        $availablePhotos[] = [
                            'filename' => strtolower($file->getFilename()),
                            'path' => $relativePath
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Handle potential iteration errors
            }
        }

        foreach ($sheet as $index => $row) {
            $rowData = $this->mapRowToFields($header, $row);
            $validator = Validator::make($rowData, [
                'full_name' => 'required|string|max:255',
                'matric_number' => 'required|string|max:50|unique:certificates,matric_number',
                'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number',
                'programme' => 'required|string|max:255',
            ]);

            $rowErrors = $validator->errors()->all();

            // Match photo using the pre-scanned list (case-insensitive)
            if (!empty($availablePhotos)) {
                $matric = $rowData['matric_number'] ?? '';
                $sanitizedWithUnderscores = strtolower($this->qrService->sanitizeMatricNumber($matric));
                $sanitizedNoSymbols = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $matric));

                $foundPhoto = null;
                foreach ($availablePhotos as $photo) {
                    if (Str::startsWith($photo['filename'], $sanitizedWithUnderscores) ||
                        Str::startsWith($photo['filename'], $sanitizedNoSymbols)) {
                        $foundPhoto = $photo['path'];
                        break;
                    }
                }
                $rowData['preview_photo'] = $foundPhoto;
            }

            $preview[] = $rowData;

            if (!empty($rowErrors)) {
                $errors[$index + 2] = $rowErrors;
            }
        }

        return [
            'data' => $preview,
            'errors' => $errors,
            'total' => count($sheet),
            'failed' => count($errors),
            'temp_path' => $tempExtractPath,
        ];
    }

    public function import(string $filePath, ?string $zipPath = null): array
    {
        // Use the dedicated Import class to satisfy the library's type requirements
        $data = Excel::toArray(new \App\Imports\CertificatesImport, $filePath);
        if (empty($data) || !isset($data[0])) {
            throw new \Exception('The uploaded file is empty or invalid.');
        }

        $sheet = $data[0];
        $header = array_shift($sheet);

        $imported = 0;
        $failed = 0;
        $photosImported = 0;
        $importErrors = [];

        $tempExtractPath = storage_path('app/temp-passports-' . uniqid());

        if ($zipPath) {
            $zipFile = Storage::disk('local')->path($zipPath);
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo($tempExtractPath);
                $zip->close();
            }
        }

        // Pre-scan for photos if ZIP was provided to handle nested folders and case-insensitivity
        $availablePhotos = [];
        if ($tempExtractPath && is_dir($tempExtractPath)) {
            try {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($tempExtractPath, \RecursiveDirectoryIterator::SKIP_DOTS)
                );
                foreach ($iterator as $file) {
                    if ($file->isFile() && preg_match('/\.(jpg|jpeg|png)$/i', $file->getFilename())) {
                        $availablePhotos[] = [
                            'filename' => strtolower($file->getFilename()),
                            'full_path' => $file->getPathname()
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Handle potential iteration errors
            }
        }

        DB::beginTransaction();
        try {
            foreach ($sheet as $index => $row) {
                $rowData = $this->mapRowToFields($header, $row);

                $validator = Validator::make($rowData, [
                    'full_name' => 'required',
                    'matric_number' => 'required|unique:certificates,matric_number',
                    'certificate_number' => 'required|unique:certificates,certificate_number',
                ]);

                if ($validator->fails()) {
                    $importErrors[] = "Row " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                    $failed++;
                    continue;
                }

                if (!empty($availablePhotos)) {
                    $matric = $rowData['matric_number'] ?? '';
                    $sanitizedWithUnderscores = strtolower($this->qrService->sanitizeMatricNumber($matric));
                    $sanitizedNoSymbols = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $matric));

                    $foundPhotoPath = null;
                    foreach ($availablePhotos as $photo) {
                        if (Str::startsWith($photo['filename'], $sanitizedWithUnderscores) ||
                            Str::startsWith($photo['filename'], $sanitizedNoSymbols)) {
                            $foundPhotoPath = $photo['full_path'];
                            break;
                        }
                    }

                    if ($foundPhotoPath) {
                        $filename = basename($foundPhotoPath);
                        $photoPath = 'passports/' . $filename;
                        Storage::disk('public')->put($photoPath, file_get_contents($foundPhotoPath));
                        $rowData['passport_photo'] = $photoPath;
                        $photosImported++;
                    }
                }

                $token = $this->qrService->generateToken();
                $certificate = Certificate::create(array_merge($rowData, [
                    'verification_token' => $token
                ]));

                $this->qrService->generateQrCode($token, $certificate->matric_number);
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            if ($tempExtractPath && is_dir($tempExtractPath)) {
                $this->deleteDirectory($tempExtractPath);
            }
        }

        return [
            'imported' => $imported,
            'failed' => $failed,
            'photos_imported' => $photosImported,
            'errors' => $importErrors,
        ];
    }

    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
        }
        return rmdir($dir);
    }

    private function mapRowToFields(array $header, array $row): array
    {
        $fields = [
            'Full Name' => 'full_name',
            'Birth Name' => 'birth_name',
            'Matric Number' => 'matric_number',
            'Faculty' => 'faculty',
            'Programme' => 'programme',
            'Department' => 'department',
            'School' => 'school',
            'Qualification' => 'qualification',
            'Award' => 'award',
            'Class' => 'class_of_award',
            'Graduation Date' => 'graduation_date',
            'Session' => 'academic_session',
            'Certificate Number' => 'certificate_number',
        ];

        $mapped = [];
        foreach ($header as $index => $columnName) {
            $trimmedColumn = trim($columnName);
            if (isset($fields[$trimmedColumn])) {
                $mapped[$fields[$trimmedColumn]] = $row[$index] ?? null;
            }
        }

        return $mapped;
    }
}