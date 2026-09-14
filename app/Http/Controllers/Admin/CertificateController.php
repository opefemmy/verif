<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\QrCodeService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    protected $qrService;
    protected $auditService;

    public function __construct(QrCodeService $qrService, AuditService $auditService)
    {
        $this->qrService = $qrService;
        $this->auditService = $auditService;
    }

    public function index(Request $request)
    {
        $query = Certificate::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('matric_number', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%")
                  ->orWhere('programme', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $certificates = $query->latest()->paginate(15);

        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        return view('admin.certificates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_name' => 'nullable|string|max:255',
            'matric_number' => 'required|string|max:50|unique:certificates,matric_number',
            'passport_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'school' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'programme' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'award' => 'nullable|string|max:255',
            'class_of_award' => 'nullable|string|max:100',
            'graduation_date' => 'nullable|date',
            'graduation_year' => 'nullable|integer',
            'academic_session' => 'nullable|string|max:50',
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number',
            'status' => 'required|in:VALID,REVOKED,PENDING,ARCHIVED',
            'remarks' => 'nullable|string',
        ]);

        $token = $this->qrService->generateToken();
        while (Certificate::where('verification_token', $token)->exists()) {
            $token = $this->qrService->generateToken();
        }

        if ($request->hasFile('passport_photo')) {
            $validated['passport_photo'] = $request->file('passport_photo')->store('passports', 'public');
        }

        $certificate = Certificate::create(array_merge($validated, [
            'verification_token' => $token
        ]));

        $this->qrService->generateQrCode($token, $certificate->matric_number);

        $this->auditService->log('certificate_created', 'Certificate', $certificate->id, ['matric_number' => $certificate->matric_number]);

        return redirect()->route('admin.certificates.index')->with('success', 'Certificate record created and QR code generated successfully.');
    }

    public function show(Certificate $certificate)
    {
        return view('admin.certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_name' => 'nullable|string|max:255',
            'matric_number' => 'required|string|max:50|unique:certificates,matric_number,' . $certificate->id,
            'passport_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'school' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'programme' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'award' => 'nullable|string|max:255',
            'class_of_award' => 'nullable|string|max:100',
            'graduation_date' => 'nullable|date',
            'graduation_year' => 'nullable|integer',
            'academic_session' => 'nullable|string|max:50',
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number,' . $certificate->id,
            'status' => 'required|in:VALID,REVOKED,PENDING,ARCHIVED',
            'remarks' => 'nullable|string',
        ]);

        if ($request->hasFile('passport_photo')) {
            if ($certificate->passport_photo) {
                Storage::disk('public')->delete($certificate->passport_photo);
            }
            $validated['passport_photo'] = $request->file('passport_photo')->store('passports', 'public');
        }

        $certificate->update($validated);

        $this->auditService->log('certificate_updated', 'Certificate', $certificate->id, ['matric_number' => $certificate->matric_number]);

        return redirect()->route('admin.certificates.index')->with('success', 'Certificate record updated successfully.');
    }

    public function destroy(Certificate $certificate)
    {
        // Permanently delete the certificate and its associated QR code
        $sanitized = preg_replace('/[^A-Za-z0-9]/', '_', $certificate->matric_number);
        Storage::disk('public')->delete('qr-codes/' . $sanitized . '.png');
        Storage::disk('public')->delete('qr-codes/' . $sanitized . '.svg');
        if ($certificate->passport_photo) {
            Storage::disk('public')->delete($certificate->passport_photo);
        }

        $certificate->forceDelete();
        $this->auditService->log('certificate_permanently_deleted', 'Certificate', $certificate->id, ['matric_number' => $certificate->matric_number]);
        return redirect()->route('admin.certificates.index')->with('success', 'Certificate permanently deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('certificates', []);
        $deleteAll = $request->boolean('delete_all');

        if ($deleteAll) {
            $certificates = Certificate::all();
        } elseif (!empty($ids)) {
            $certificates = Certificate::whereIn('id', $ids)->get();
        } else {
            return redirect()->back()->with('error', 'Please select certificates to delete.');
        }

        $count = $certificates->count();

        foreach ($certificates as $certificate) {
            $sanitized = preg_replace('/[^A-Za-z0-9]/', '_', $certificate->matric_number);
            Storage::disk('public')->delete('qr-codes/' . $sanitized . '.png');
            Storage::disk('public')->delete('qr-codes/' . $sanitized . '.svg');
            if ($certificate->passport_photo) {
                Storage::disk('public')->delete($certificate->passport_photo);
            }
            $certificate->forceDelete();
        }

        $this->auditService->log('bulk_certificates_deleted', 'Certificate', null, ['count' => $count]);

        return redirect()->route('admin.certificates.index')->with('success', "Successfully deleted $count certificates.");
    }

    public function revoke(Certificate $certificate)
    {
        $certificate->update(['status' => 'REVOKED']);
        $this->auditService->log('certificate_revoked', 'Certificate', $certificate->id, ['matric_number' => $certificate->matric_number]);
        return redirect()->back()->with('success', 'Certificate has been revoked.');
    }

    public function restore(Certificate $certificate)
    {
        $certificate->update(['status' => 'VALID']);
        $this->auditService->log('certificate_restored', 'Certificate', $certificate->id, ['matric_number' => $certificate->matric_number]);
        return redirect()->back()->with('success', 'Certificate has been restored to VALID status.');
    }

    public function downloadQr(Certificate $certificate)
    {
        $sanitized = $this->qrService->sanitizeMatricNumber($certificate->matric_number);

        $pngFilename = $sanitized . '.png';
        $svgFilename = $sanitized . '.svg';
        $pngPath = 'qr-codes/' . $pngFilename;
        $svgPath = 'qr-codes/' . $svgFilename;

        // If PNG is missing, try to regenerate it
        if (!Storage::disk('public')->exists($pngPath)) {
            try {
                $this->qrService->regenerateQrCode($certificate->verification_token, $certificate->matric_number);
            } catch (\Exception $e) {
                \Log::error("QR PNG Regeneration failed for certificate {$certificate->id}: " . $e->getMessage());
            }
        }

        if (Storage::disk('public')->exists($pngPath)) {
            $filename = $pngFilename;
            $fullPath = Storage::disk('public')->path($pngPath);
        } elseif (Storage::disk('public')->exists($svgPath)) {
            $filename = $svgFilename;
            $fullPath = Storage::disk('public')->path($svgPath);
        } else {
            return redirect()->back()->with('error', 'QR code file not found. Please regenerate it.');
        }

        // Verify the file exists on the actual filesystem before attempting download
        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'The QR code file was not found on the server. Please try regenerating it.');
        }

        $this->auditService->log('qr_downloaded', 'Certificate', $certificate->id, ['filename' => $filename]);

        // Use response()->download() with absolute path for better reliability on Windows/Artisan Serve
        return response()->download($fullPath, $filename);
    }

    public function regenerateQr(Certificate $certificate)
    {
        $this->qrService->regenerateQrCode($certificate->verification_token, $certificate->matric_number);
        $this->auditService->log('qr_regenerated', 'Certificate', $certificate->id, ['matric_number' => $certificate->matric_number]);
        return redirect()->back()->with('success', 'QR code regenerated successfully.');
    }

    public function downloadPdf(Certificate $certificate)
    {
        $settings = \App\Models\InstitutionSetting::first();

        // Use the professional design created in resources/views/admin/certificates/pdf.blade.php
        $pdf = Pdf::loadView('admin.certificates.pdf', compact('certificate', 'settings'));

        // Sanitize matric number for the filename to prevent "InvalidArgumentException"
        // caused by slashes (e.g., HND/CS/2024/0001)
        $sanitizedMatric = preg_replace('/[^A-Za-z0-9]/', '_', $certificate->matric_number);

        return $pdf->download('Certificate_' . $sanitizedMatric . '.pdf');
    }

    public function downloadBulkPdf(Request $request)
    {
        $ids = $request->input('certificates', []);
        $downloadAll = $request->boolean('download_all');

        if ($downloadAll) {
            $certificates = Certificate::all();
        } elseif (!empty($ids)) {
            $certificates = Certificate::whereIn('id', $ids)->get();
        } else {
            return redirect()->back()->with('error', 'Please select certificates to download.');
        }

        $settings = \App\Models\InstitutionSetting::first();

        $pdf = Pdf::loadView('admin.certificates.bulk_pdf', compact('certificates', 'settings'));

        return $pdf->download('Certificates_Bulk_' . now()->format('Ymd_His') . '.pdf');
    }
}