<x-admin-layout>
    <x-slot name="header">Certificate Details</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 transition-colors">← Back to List</a>
            <div class="flex gap-3">
                <form action="{{ route('admin.certificates.revoke', $certificate) }}" method="POST" onsubmit="return confirm('Are you sure you want to revoke this certificate?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 transition-colors {{ $certificate->status == 'REVOKED' ? 'hidden' : '' }}">
                        Revoke Certificate
                    </button>
                </form>
                <form action="{{ route('admin.certificates.restore', $certificate) }}" method="POST" onsubmit="return confirm('Are you sure you want to restore this certificate to VALID status?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 transition-colors {{ $certificate->status != 'REVOKED' ? 'hidden' : '' }}">
                        Restore to Valid
                    </button>
                </form>
                <a href="{{ route('admin.certificates.edit', $certificate) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors">Edit Record</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Passport & QR -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200 text-center">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Passport Photograph</h3>
                    @if($certificate->passport_photo)
                        <img src="{{ Storage::url($certificate->passport_photo) }}" class="w-48 h-48 mx-auto rounded-lg object-cover border-4 border-white shadow-md" alt="Passport">
                    @else
                        <div class="w-48 h-48 mx-auto bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border-2 border-dashed">No Photo Uploaded</div>
                    @endif
                </div>

                <div class="bg-white p-6 rounded-lg shadow border border-gray-200 text-center">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Verification QR Code</h3>
                    <div class="bg-white p-4 inline-block rounded-lg border shadow-inner mb-4">
                        <img src="{{ Storage::url('qr-codes/' . preg_replace('/[^A-Za-z0-9]/', '_', $certificate->matric_number) . '.png') }}"
                             onerror="this.src='{{ Storage::url('qr-codes/' . preg_replace('/[^A-Za-z0-9]/', '_', $certificate->matric_number) . '.svg') }}'"
                             class="w-48 h-48" alt="QR Code">
                    </div>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('admin.certificates.download-qr', $certificate) }}" class="w-full px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 transition-colors">Download QR Code</a>
                        <form action="{{ route('admin.certificates.regenerate-qr', $certificate) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors border border-gray-300">Regenerate QR</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Academic & Personal Details</h3>
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $certificate->status == 'VALID' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $certificate->status }}
                        </span>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Full Name</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->full_name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Matric Number</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->matric_number }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Certificate Number</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->certificate_number }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Programme</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->programme }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Faculty</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->faculty }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Department</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->department }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Qualification</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->qualification }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Class of Award</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->class_of_award }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Graduation Date</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->graduation_date ? \Carbon\Carbon::parse($certificate->graduation_date)->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Academic Session</span>
                            <p class="text-sm text-gray-900 font-medium">{{ $certificate->academic_session }}</p>
                        </div>
                        <div class="md:col-span-2 space-y-1 pt-4 border-t">
                            <span class="text-xs text-gray-500 uppercase font-semibold">Verification Token</span>
                            <div class="flex items-center gap-2">
                                <code class="text-sm bg-gray-100 px-2 py-1 rounded border font-mono">{{ $certificate->verification_token }}</code>
                                <a href="{{ route('verify', ['token' => $certificate->verification_token]) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Test QR/Link ↗</a>
                            </div>
                        </div>
                    </div>
                </div>

                @if($certificate->remarks)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <h4 class="text-sm font-bold text-yellow-800 uppercase">Administrative Remarks</h4>
                        <p class="text-sm text-yellow-700">{{ $certificate->remarks }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>