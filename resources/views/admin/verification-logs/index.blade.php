<x-admin-layout>
    <x-slot name="header">Verification Logs</x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <form method="GET" action="{{ route('admin.verification-logs.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:flex-1 min-w-[250px]">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Search Logs</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search token, IP, or student name..." class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm">
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Result</label>
                    <select name="result" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm bg-white">
                        <option value="">All Results</option>
                        <option value="SUCCESS" {{ request('result') == 'SUCCESS' ? 'selected' : '' }}>Success</option>
                        <option value="NOT_FOUND" {{ request('result') == 'NOT_FOUND' ? 'selected' : '' }}>Not Found</option>
                    </select>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm">Filter</button>
                    <a href="{{ route('admin.verification-logs.index') }}" class="flex-1 sm:flex-none px-6 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-200 transition-colors text-center">Reset</a>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Token</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Result</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $log->created_at->format('d M Y, H:i:s') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-slate-900">{{ $log->certificate->full_name ?? 'Unknown' }}</span>
                                        <span class="text-xs text-slate-500">{{ $log->certificate->matric_number ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs bg-slate-100 px-2 py-1 rounded border font-mono text-slate-600">{{ $log->verification_token }}</code>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-mono">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->result === 'SUCCESS')
                                        <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-green-100 text-green-700 uppercase">Success</span>
                                    @elseif($log->result === 'NOT_FOUND')
                                        <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-red-100 text-red-700 uppercase">Not Found</span>
                                    @else
                                        <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-gray-100 text-gray-700 uppercase">{{ $log->result }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-4xl mb-3 opacity-20">🕒</div>
                                    <p class="text-slate-500 text-sm">No verification logs found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>