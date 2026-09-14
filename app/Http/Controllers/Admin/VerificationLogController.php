<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationLog;
use Illuminate\Http\Request;

class VerificationLogController extends Controller
{
    public function index(Request $request)
    {
        $query = VerificationLog::with('certificate');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('verification_token', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('certificate', function($q) use ($search) {
                      $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('matric_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('result')) {
            $query->where('result', $request->result);
        }

        $logs = $query->latest()->paginate(20);

        return view('admin.verification-logs.index', compact('logs'));
    }
}
