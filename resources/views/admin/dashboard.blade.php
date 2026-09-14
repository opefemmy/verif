<x-admin-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-5 group hover:border-blue-400 transition-all">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🎓</div>
                <div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Certificates</div>
                    <div class="text-3xl font-bold text-slate-900">{{ $stats['total'] }}</div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-5 group hover:border-green-400 transition-all">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">✅</div>
                <div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Valid Certificates</div>
                    <div class="text-3xl font-bold text-slate-900">{{ $stats['valid'] }}</div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-5 group hover:border-red-400 transition-all">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🚫</div>
                <div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Revoked Certificates</div>
                    <div class="text-3xl font-bold text-slate-900">{{ $stats['revoked'] }}</div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-5 group hover:border-indigo-400 transition-all">
                <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🔍</div>
                <div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Verifications</div>
                    <div class="text-3xl font-bold text-slate-900">{{ $stats['verifications'] }}</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Certificates -->
            <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span class="text-blue-500">🎓</span> Recent Certificates
                    </h3>
                    <a href="{{ route('admin.certificates.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors uppercase tracking-wider">View All</a>
                </div>
                <div class="p-6">
                    @if($recentCertificates->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-slate-400 border-b border-slate-100">
                                        <th class="pb-3 font-medium">Student</th>
                                        <th class="pb-3 font-medium">Matric No</th>
                                        <th class="pb-3 font-medium text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($recentCertificates as $cert)
                                        <tr>
                                            <td class="py-3 font-medium text-slate-700">{{ $cert->full_name }}</td>
                                            <td class="py-3 text-slate-500">{{ $cert->matric_number }}</td>
                                            <td class="py-3 text-right">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $cert->is_revoked ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                                    {{ $cert->is_revoked ? 'Revoked' : 'Active' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-4xl mb-3 opacity-20">📂</div>
                            <p class="text-slate-500 text-sm">No recent certificates found in the system.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Verification Activity -->
            <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span class="text-indigo-500">📜</span> Verification Activity
                    </h3>
                    <a href="{{ route('admin.verification-logs.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors uppercase tracking-wider">View All</a>
                </div>
                <div class="p-6">
                    @if($recentVerifications->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-slate-400 border-b border-slate-100">
                                        <th class="pb-3 font-medium">Token</th>
                                        <th class="pb-3 font-medium">IP Address</th>
                                        <th class="pb-3 font-medium text-right">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($recentVerifications as $log)
                                        <tr>
                                            <td class="py-3 font-mono text-xs text-slate-600">{{ $log->verification_token }}</td>
                                            <td class="py-3 text-slate-500">{{ $log->ip_address }}</td>
                                            <td class="py-3 text-right text-slate-400">{{ $log->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-4xl mb-3 opacity-20">🕒</div>
                            <p class="text-slate-500 text-sm">No recent verification activity recorded.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>