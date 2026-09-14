<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $settings->verification_title ?? 'Certificate Verification' }} - {{ $settings->institution_name ?? 'Verification Portal' }}</title>
        @if($settings && $settings->logo)
            <link rel="icon" href="{{ Storage::url($settings->logo) }}?v={{ time() }}">
        @endif
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
            body { font-family: 'Inter', sans-serif; }
            .verification-card {
                background: white;
                border-radius: 1.5rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            }
            .institutional-gradient {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            }
        </style>
    </head>
    <body class="bg-slate-100 text-slate-900 min-h-screen flex flex-col">
        <x-splash />
        <!-- Header Section -->
        <header class="institutional-gradient text-white py-16 px-4 text-center shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
            <div class="max-w-4xl mx-auto relative z-10">
                @if($settings && $settings->logo)
                    <img src="{{ Storage::url($settings->logo) }}" class="h-28 w-auto mx-auto mb-6" alt="Institution Logo">
                @endif
                @php
                    $rawName = $settings->institution_name ?? 'Institutional';
                    if (str_contains($rawName, ',')) {
                        $parts = explode(',', $rawName, 2);
                        $displayName = trim($parts[0]);
                        $displayLocation = trim($parts[1]);
                    } else {
                        $displayName = $rawName;
                        $displayLocation = $settings->city ? ($settings->city . ' - ' . $settings->state) : ($settings->location ?? '');
                    }
                @endphp
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">{{ $displayName }},</h1>
                @if($displayLocation)
                    <div class="text-slate-300 mt-2 text-lg font-medium leading-tight">
                        {{ $displayLocation }}
                    </div>
                @endif
                <p class="text-slate-400 mt-3 text-xl font-light italic">{{ $settings->motto ?? 'Official Certificate Verification Portal' }}</p>
            </div>
        </header>

        <main class="flex-1 px-4 py-12 -mt-12">
            <div class="max-w-4xl mx-auto">
                @if($verification['status'] === 'NOT_FOUND')
                    <!-- NOT FOUND STATE -->
                    <div class="verification-card p-16 text-center space-y-6 border-t-8 border-red-500">
                        <div class="text-7xl mb-4">🔍</div>
                        <h2 class="text-3xl font-extrabold text-slate-800">Certificate Not Found</h2>
                        <p class="text-slate-600 text-lg max-w-md mx-auto">We could not find a certificate record associated with this verification token. Please ensure the QR code is authentic or contact the institution.</p>
                        <div class="pt-8">
                            <a href="{{ $settings->website ?? '#' }}" class="px-8 py-3 bg-slate-900 text-white rounded-full font-bold hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl">Visit Official Website</a>
                        </div>
                    </div>
                @else
                    @php $cert = $verification['certificate']; @endphp

                    <!-- VERIFIED/REVOKED STATE -->
                    <div class="verification-card relative overflow-hidden border-t-8 {{ $cert->status === 'VALID' ? 'border-green-500' : 'border-red-500' }}">

                        <!-- Institutional Watermark -->
                        @if($settings && $settings->logo)
                            <div class="absolute inset-0 flex items-center justify-center opacity-[0.07] pointer-events-none z-0">
                                <img src="{{ Storage::url($settings->logo) }}" class="w-2/3 h-auto" alt="Watermark">
                            </div>
                        @endif

                        <div class="relative z-10">
                            <!-- Status Banner -->
                            <div class="p-10 text-center {{ $cert->status === 'VALID' ? 'bg-green-50' : 'bg-red-50' }}">
                                @if($cert->status === 'VALID')
                                    <div class="text-6xl mb-4">✅</div>
                                    <h2 class="text-4xl font-black text-green-800 uppercase tracking-tighter">Certificate Verified</h2>
                                    <p class="text-green-700 mt-2 text-lg font-medium">This certificate has been successfully verified against the institution's official records.</p>
                                @else
                                    <div class="text-6xl mb-4">⚠</div>
                                    <h2 class="text-4xl font-black text-red-800 uppercase tracking-tighter">Certificate Revoked</h2>
                                    <p class="text-red-700 mt-2 text-lg font-medium">This certificate is no longer valid and has been revoked by the issuing authority.</p>
                                @endif
                                <div class="mt-6 inline-block px-6 py-1.5 rounded-full text-sm font-black uppercase tracking-widest {{ $cert->status === 'VALID' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    Status: {{ $cert->status }}
                                </div>
                            </div>

                            <!-- Details Section -->
                            <div class="p-8 md:p-16">
                                <div class="flex flex-col lg:flex-row gap-16 items-center lg:items-start">
                                    <!-- Passport Photo -->
                                    @if($cert->passport_photo)
                                    <div class="w-full lg:w-1/3 flex flex-col items-center">
                                        <div class="relative p-2 bg-white rounded-2xl shadow-2xl border border-slate-100">
                                            <img src="{{ Storage::url($cert->passport_photo) }}" class="w-56 h-56 rounded-xl object-cover" alt="Passport Photo">
                                            <div class="absolute -bottom-4 -right-4 bg-white p-3 rounded-full shadow-lg border border-slate-100">
                                                <span class="text-3xl">🎓</span>
                                            </div>
                                        </div>
                                        <div class="mt-8 text-center">
                                            <h3 class="text-2xl font-extrabold text-slate-900">{{ $cert->full_name }}</h3>
                                            <p class="text-sm font-mono text-slate-500 bg-slate-100 px-3 py-1 rounded-full inline-block mt-2">{{ $cert->matric_number }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Academic Info -->
                                    <div class="w-full {{ $cert->passport_photo ? 'lg:w-2/3' : 'lg:w-full' }} space-y-8">
                                        @if(!$cert->passport_photo)
                                            <div class="text-center mb-12">
                                                <h3 class="text-4xl md:text-6xl font-black text-slate-900 mb-4">{{ $cert->full_name }}</h3>
                                                <p class="text-lg font-mono text-slate-600 bg-slate-200 px-6 py-2 rounded-full inline-block font-bold">{{ $cert->matric_number }}</p>
                                            </div>
                                        @endif
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-8">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Programme</span>
                                                <p class="text-slate-800 font-semibold text-base">{{ $cert->programme ?? 'N/A' }}</p>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Qualification</span>
                                                <p class="text-slate-800 font-semibold text-base">{{ $cert->qualification ?? 'N/A' }}</p>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Faculty</span>
                                                <p class="text-slate-800 font-semibold text-base">{{ $cert->faculty ?? 'N/A' }}</p>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Class of Award</span>
                                                <p class="text-slate-800 font-semibold text-base">{{ $cert->class_of_award ?? 'N/A' }}</p>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Graduation Date</span>
                                                <p class="text-slate-800 font-semibold text-base">{{ $cert->graduation_date ? \Carbon\Carbon::parse($cert->graduation_date)->format('d M Y') : 'N/A' }}</p>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Academic Session</span>
                                                <p class="text-slate-800 font-semibold text-base">{{ $cert->academic_session ?? 'N/A' }}</p>
                                            </div>
                                            <div class="sm:col-span-2 flex flex-col pt-4 border-t border-slate-100">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Certificate Number</span>
                                                <p class="text-slate-900 font-mono font-bold text-lg tracking-wider">{{ $cert->certificate_number }}</p>
                                            </div>
                                        </div>

                                        @if($cert->remarks)
                                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 relative overflow-hidden">
                                                <div class="absolute top-0 left-0 w-1 h-full bg-slate-300"></div>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Administrative Remarks</span>
                                                <p class="text-sm text-slate-600 italic leading-relaxed">{{ $cert->remarks }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white py-16 px-4 border-t border-slate-200">
            <div class="max-w-3xl mx-auto text-center space-y-8">
                <div class="flex flex-col items-center gap-3">
                    <p class="text-slate-500 text-sm font-medium">Verified electronically through the official</p>
                    <p class="text-slate-900 font-black text-2xl tracking-tight">{{ $settings->institution_name ?? 'Institution' }} Certificate Verification Portal</p>
                </div>

                <div class="text-xs text-slate-400 space-y-2 font-medium">
                    <p class="flex items-center justify-center gap-2">
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        Verification Date: {{ $verifiedAt->format('d M Y, H:i:s') }}
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    </p>
                    <p class="max-w-md mx-auto italic opacity-75">{{ $settings->verification_footer ?? 'Official record provided for verification purposes only.' }}</p>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-wrap justify-center gap-x-12 gap-y-6 text-sm font-semibold text-slate-500">
                    <a href="{{ $settings->website ?? '#' }}" class="hover:text-blue-600 transition-colors flex items-center gap-2">
                        <span>🌐</span> Official Website
                    </a>
                    <a href="mailto:{{ $settings->official_email ?? '#' }}" class="hover:text-blue-600 transition-colors flex items-center gap-2">
                        <span>✉️</span> Contact Support
                    </a>
                    <span class="flex items-center gap-2">
                        <span>📞</span> {{ $settings->phone ?? '' }}
                    </span>
                </div>
            </div>
        </footer>
    </body>
</html>