<x-admin-layout>
    <x-slot name="header">Import Preview</x-slot>

    <div class="max-w-6xl mx-auto space-y-6">
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Review Your Data</h3>
                <p class="text-sm text-gray-500">Please check the records below for any errors before confirming the import.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.import.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 transition-colors">Cancel</a>
                <form action="{{ route('admin.import.confirm') }}" method="POST">
                    @csrf
                    <input type="hidden" name="path" value="{{ $path }}">
                    @if(isset($zipPath))
                        <input type="hidden" name="zip_path" value="{{ $zipPath }}">
                    @endif
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm">Confirm & Import All</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Summary Stats -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Total Records</div>
                    <div class="text-3xl font-bold text-slate-900">{{ $preview['total'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Valid Records</div>
                    <div class="text-3xl font-bold text-green-600">{{ $preview['total'] - $preview['failed'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Failed Records</div>
                    <div class="text-3xl font-bold text-red-600">{{ $preview['failed'] }}</div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="lg:col-span-3 bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Row</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Photo</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Full Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Matric Number</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($preview['data'] as $index => $row)
                                @php $rowNum = $index + 2; @endphp
                                <tr class="{{ isset($preview['errors'][$rowNum]) ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $rowNum }}</td>
                                    <td class="px-6 py-4">
                                        @if(!empty($row['preview_photo']) && $temp_path)
                                            <img src="{{ route('admin.import.photo', ['photo' => $row['preview_photo'], 'temp_path' => $temp_path]) }}"
                                                 alt="Passport"
                                                 class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs border border-gray-200">
                                                No Photo
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $row['full_name'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $row['matric_number'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        @if(isset($preview['errors'][$rowNum]))
                                            <span class="flex flex-col gap-1">
                                                @foreach($preview['errors'][$rowNum] as $error)
                                                    <span class="text-[10px] text-red-600 font-semibold leading-tight">• {{ $error }}</span>
                                                @endforeach
                                            </span>
                                        @else
                                            <span class="text-xs text-green-600 font-bold uppercase">✓ Valid</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>