<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CertificateImportService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CertificateTemplateExport;

class ImportController extends Controller
{
    protected $importService;
    protected $auditService;

    public function __construct(CertificateImportService $importService, AuditService $auditService)
    {
        $this->importService = $importService;
        $this->auditService = $auditService;
    }

    public function index()
    {
        return view('admin.import.index');
    }

    public function downloadTemplate()
    {
        return Excel::download(new CertificateTemplateExport, 'certificate_import_template.xlsx');
    }

    public function preview(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.import.index')->with('error', 'Please upload a file first to preview the import.');
        }

        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv|max:10240',
            'passport_zip' => 'nullable|mimes:zip|max:51200',
        ]);

        $file = $request->file('import_file');
        $path = $file->store('temp-imports', 'local');

        $zipPath = null;
        if ($request->hasFile('passport_zip')) {
            $zipPath = $request->file('passport_zip')->store('temp-imports', 'local');
        }

        try {
            $fullPath = Storage::disk('local')->path($path);
            $preview = $this->importService->previewImport($fullPath, $zipPath);
            return view('admin.import.preview', compact('preview', 'path'))->with([
                'zipPath' => $zipPath,
                'temp_path' => $preview['temp_path'] ?? null
            ]);
        } catch (\Exception $e) {
            Storage::disk('local')->delete($path);
            if ($zipPath) Storage::disk('local')->delete($zipPath);
            return redirect()->back()->with('error', 'Failed to parse file: ' . $e->getMessage());
        }
    }

    public function showPreviewPhoto(Request $request)
    {
        $photo = $request->query('photo');
        $tempPath = $request->query('temp_path');

        if (!$photo || !$tempPath) {
            abort(400, 'Missing photo or temp path');
        }

        // Normalize paths for Windows compatibility
        $normalizedTempPath = str_replace('/', DIRECTORY_SEPARATOR, $tempPath);
        $normalizedPhoto = str_replace('/', DIRECTORY_SEPARATOR, $photo);
        $fullPath = $normalizedTempPath . DIRECTORY_SEPARATOR . $normalizedPhoto;

        // Security check: Ensure the file is actually inside the temp directory
        $realTempPath = realpath($normalizedTempPath);
        $realFullPath = realpath($fullPath);

        if (!$realTempPath || !$realFullPath || !str_starts_with($realFullPath, $realTempPath)) {
            abort(403, 'Invalid photo path');
        }

        if (!file_exists($realFullPath)) {
            abort(404, 'Photo not found');
        }

        return response()->file($realFullPath);
    }

    public function confirm(Request $request)
    {
        $path = $request->input('path');
        $zipPath = $request->input('zip_path');

        if (!$path || !Storage::disk('local')->exists($path)) {
            return redirect()->route('admin.import.index')->with('error', 'Import file not found. Please upload your files again.');
        }

        try {
            $result = $this->importService->import(Storage::disk('local')->path($path), $zipPath);
            Storage::disk('local')->delete($path);
            if ($zipPath) Storage::disk('local')->delete($zipPath);

            $this->auditService->log('bulk_import_performed', null, null, [
                'imported' => $result['imported'],
                'failed' => $result['failed'],
                'photos_imported' => $result['photos_imported'] ?? 0,
            ]);

            return redirect()->route('admin.import.index')->with('success', "Import complete! Imported: {$result['imported']}, Failed: {$result['failed']}");
        } catch (\Exception $e) {
            // Redirect to index instead of back() to avoid triggering the "Please upload a file first" guard in preview()
            return redirect()->route('admin.import.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}