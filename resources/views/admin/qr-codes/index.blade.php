<x-admin-layout>
    <x-slot name="header">QR Code Management</x-slot>

    <div class="space-y-6">
        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('admin.qr-codes.download-all') }}" method="GET">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                        <span>📦</span> Download All
                    </button>
                </form>
                <form action="{{ route('admin.qr-codes.download-selected') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-2">
                        <span>✔️</span> Download Selected
                    </button>
                </form>
            </div>
            <p class="text-sm text-gray-500 text-center md:text-right">Manage and bulk download your certificate QR codes as PNG images.</p>
        </div>

        <!-- Filter Form -->
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('admin.qr-codes.download-filtered') }}" class="space-y-6">
                @csrf
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Filter for Bulk Download</h3>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                        <span>📥</span> Download Filtered QR Codes
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">School</label>
                        <input type="text" name="school" value="{{ request('school') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Faculty</label>
                        <input type="text" name="faculty" value="{{ request('faculty') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Department</label>
                        <input type="text" name="department" value="{{ request('department') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Programme</label>
                        <input type="text" name="programme" value="{{ request('programme') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Qualification</label>
                        <input type="text" name="qualification" value="{{ request('qualification') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Session</label>
                        <input type="text" name="session" value="{{ request('session') }}" placeholder="e.g. 2024/2025" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Grad Year</label>
                        <input type="number" name="year" value="{{ request('year') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="VALID" {{ request('status') == 'VALID' ? 'selected' : '' }}>Valid</option>
                            <option value="REVOKED" {{ request('status') == 'REVOKED' ? 'selected' : '' }}>Revoked</option>
                            <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="ARCHIVED" {{ request('status') == 'ARCHIVED' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Records Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
            <form method="POST" action="{{ route('admin.qr-codes.download-selected') }}">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3 text-center w-10">
                                    <input type="checkbox" id="select-all" class="rounded text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3">Graduate</th>
                                <th class="px-6 py-3">Matric Number</th>
                                <th class="px-6 py-3">QR Filename</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($certificates as $certificate)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" name="certificates[]" value="{{ $certificate->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $certificate->full_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $certificate->matric_number }}</td>
                                    <td class="px-6 py-4 text-xs font-mono text-gray-500">{{ str_replace('/', '-', $certificate->matric_number) }}.png</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $certificate->status == 'VALID' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $certificate->status == 'REVOKED' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $certificate->status == 'PENDING' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $certificate->status == 'ARCHIVED' ? 'bg-gray-100 text-gray-700' : '' }}">
                                            {{ $certificate->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        No certificates found matching filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $certificates->links() }}
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="certificates[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</x-admin-layout>