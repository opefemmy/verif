<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\BulkDownloadService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    protected $bulkService;
    protected $auditService;

    public function __construct(BulkDownloadService $bulkService, AuditService $auditService)
    {
        $this->bulkService = $bulkService;
        $this->auditService = $auditService;
    }

    public function index(Request $request)
    {
        $query = Certificate::query();

        if ($request->filled('school')) $query->where('school', $request->school);
        if ($request->filled('faculty')) $query->where('faculty', $request->faculty);
        if ($request->filled('department')) $query->where('department', $request->department);
        if ($request->filled('programme')) $query->where('programme', $request->programme);
        if ($request->filled('qualification')) $query->where('qualification', $request->qualification);
        if ($request->filled('session')) $query->where('academic_session', $request->session);
        if ($request->filled('year')) $query->where('graduation_year', $request->year);
        if ($request->filled('status')) $query->where('status', $request->status);

        $certificates = $query->paginate(15);

        return view('admin.qr-codes.index', compact('certificates'));
    }

    public function downloadAll()
    {
        $certificates = Certificate::all();
        $zipFileName = $this->bulkService->downloadQrCodes($certificates, 'All-QR-Codes');

        $this->auditService->log('bulk_qr_download_all', null, null, ['count' => $certificates->count()]);

        return Storage::disk('public')->download($zipFileName);
    }

    public function downloadFiltered(Request $request)
    {
        $query = Certificate::query();

        if ($request->filled('school')) $query->where('school', $request->school);
        if ($request->filled('faculty')) $query->where('faculty', $request->faculty);
        if ($request->filled('department')) $query->where('department', $request->department);
        if ($request->filled('programme')) $query->where('programme', $request->programme);
        if ($request->filled('qualification')) $query->where('qualification', $request->qualification);
        if ($request->filled('session')) $query->where('academic_session', $request->session);
        if ($request->filled('year')) $query->where('graduation_year', $request->year);
        if ($request->filled('status')) $query->where('status', $request->status);

        $certificates = $query->get();

        if ($certificates->isEmpty()) {
            return redirect()->back()->with('error', 'No certificates found matching the selected filters.');
        }

        $zipFileName = $this->bulkService->downloadQrCodes($certificates, 'Filtered-QR-Codes');

        $this->auditService->log('bulk_qr_download_filtered', null, null, [
            'count' => $certificates->count(),
            'filters' => $request->all(),
        ]);

        return Storage::disk('public')->download($zipFileName);
    }

    public function downloadSelected(Request $request)
    {
        $ids = $request->input('certificates', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Please select at least one certificate.');
        }

        $certificates = Certificate::whereIn('id', $ids)->get();
        $zipFileName = $this->bulkService->downloadQrCodes($certificates, 'Selected-QR-Codes');

        $this->auditService->log('bulk_qr_download_selected', null, null, ['count' => $certificates->count()]);

        return Storage::disk('public')->download($zipFileName);
    }
}