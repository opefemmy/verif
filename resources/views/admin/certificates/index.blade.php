<x-admin-layout>
    <x-slot name="header">Graduate Certificates</x-slot>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="flex flex-col sm:flex-row gap-4 flex-1 w-full max-w-2xl">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, matric, cert number..." class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute left-3 top-2.5 text-gray-400">🔍</div>
            </div>
            <div class="flex gap-2">
                <select name="status" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Statuses</option>
                    <option value="VALID" {{ request('status') == 'VALID' ? 'selected' : '' }}>Valid</option>
                    <option value="REVOKED" {{ request('status') == 'REVOKED' ? 'selected' : '' }}>Revoked</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="ARCHIVED" {{ request('status') == 'ARCHIVED' ? 'selected' : '' }}>Archived</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Clear</a>
                @endif
            </div>
        </form>
        <a href="{{ route('admin.certificates.create') }}" class="w-full lg:w-auto text-center px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-sm flex items-center justify-center gap-2">
            <span>➕</span> Add Graduate
        </a>
    </div>

    <!-- Bulk Actions -->
    <div class="mb-6 flex flex-col sm:flex-row justify-end gap-3">
        <button type="button" onclick="setBulkAction('selected')" class="w-full sm:w-auto px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-bold hover:bg-red-200 transition-colors border border-red-200">Delete Selected</button>
        <button type="button" onclick="setBulkAction('all')" class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition-colors shadow-sm">Delete All Records</button>
    </div>

    <!-- Hidden form for bulk actions -->
    <form id="bulk-delete-form" action="{{ route('admin.certificates.bulk-delete') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="delete_all" id="delete-all-input" value="0">
        <div id="selected-ids-container"></div>
    </form>

    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="align-middle">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all-checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Passport</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Graduate</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Matric Number</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Programme</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($certificates as $certificate)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="certificates[]" value="{{ $certificate->id }}" class="cert-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4">
                                @if($certificate->passport_photo)
                                    <img src="{{ Storage::url($certificate->passport_photo) }}" class="h-10 w-10 rounded-full object-cover border" alt="Passport">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs">No Photo</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $certificate->full_name }}</div>
                                <div class="text-xs text-gray-500">Cert: {{ $certificate->certificate_number }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $certificate->matric_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $certificate->programme }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $certificate->status == 'VALID' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $certificate->status == 'REVOKED' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $certificate->status == 'PENDING' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $certificate->status == 'ARCHIVED' ? 'bg-gray-100 text-gray-700' : '' }}">
                                    {{ $certificate->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.certificates.show', $certificate) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">View</a>
                                <a href="{{ route('admin.certificates.edit', $certificate) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                <a href="{{ route('admin.certificates.download-qr', $certificate) }}" class="text-green-600 hover:text-green-900 text-sm font-medium" title="Download QR">📥 QR</a>
                                <form action="{{ route('admin.certificates.destroy', $certificate) }}" method="POST" class="inline-block" onsubmit="return confirm('Permanently delete this record? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No certificate records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $certificates->links() }}
        </div>
    </div>

    <script>
        document.getElementById('select-all-checkbox').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.cert-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        function setBulkAction(type) {
            const form = document.getElementById('bulk-delete-form');
            const allInput = document.getElementById('delete-all-input');
            const container = document.getElementById('selected-ids-container');

            container.innerHTML = ''; // Clear previous selections

            if (type === 'all') {
                if (!confirm('Are you sure you want to delete ALL certificates? This action is permanent and cannot be undone.')) {
                    return;
                }
                allInput.value = '1';
            } else {
                const checked = document.querySelectorAll('.cert-checkbox:checked');
                if (checked.length === 0) {
                    alert('Please select at least one certificate to delete.');
                    return;
                }
                if (!confirm('Delete all selected records?')) {
                    return;
                }
                allInput.value = '0';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'certificates[]';
                    input.value = cb.value;
                    container.appendChild(input);
                });
            }
            form.submit();
        }
    </script>
</x-admin-layout>