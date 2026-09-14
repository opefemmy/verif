<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $settings->verification_title ?? 'Certificate Verification Portal' }}</title>
        @if($settings && $settings->logo)
            <link rel="icon" href="{{ Storage::url($settings->logo) }}?v={{ time() }}">
        @endif

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background-color: #f8fafc;
                margin: 0;
            }
            /* Fail-safe styles in case CDN fails */
            .hero-gradient {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
                color: white !important;
                padding: 6rem 1rem !important;
                text-align: center !important;
            }
            .verification-search-box {
                max-width: 600px;
                margin: 2rem auto;
                display: flex;
                gap: 10px;
                background: white;
                padding: 8px;
                border-radius: 50px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            }
            .verification-search-box input {
                flex: 1;
                border: none;
                padding: 12px 20px;
                border-radius: 50px;
                outline: none;
                font-size: 16px;
                color: #0f172a;
            }
            .verification-search-box button {
                background: #2563eb;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 50px;
                font-weight: bold;
                cursor: pointer;
            }
            .feature-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                max-width: 1100px;
                margin: 4rem auto;
                padding: 0 1rem;
            }
            .feature-card {
                background: white;
                padding: 2rem;
                border-radius: 1.5rem;
                text-align: center;
                border: 1px solid #e2e8f0;
                transition: transform 0.2s;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            }
            .header-nav {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem 2rem;
                background: white;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body class="bg-slate-50 font-sans text-slate-900 min-h-screen flex flex-col">
        <x-splash />
        <!-- Header -->
        <header class="header-nav sticky top-0 z-50">
            <div class="flex items-center gap-3">
                @if($settings && $settings->logo)
                    <img src="{{ Storage::url($settings->logo) }}" class="h-10 w-auto" alt="Logo">
                @endif
                <span class="font-bold text-xl text-slate-800">{{ $settings->institution_name ?? 'Institution' }}</span>
            </div>
            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors bg-slate-100 px-4 py-2 rounded-full">Admin Login</a>
        </header>

        <!-- Hero Section -->
        <section class="hero-gradient relative overflow-hidden text-center">
            <div class="max-w-4xl mx-auto relative z-10 space-y-10 py-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-widest border border-blue-500/30 mb-4">
                    @if($settings && $settings->logo)
                        <img src="{{ Storage::url($settings->logo) }}" class="h-4 w-auto" alt="Logo">
                    @else
                        <span>🏛️</span>
                    @endif
                    <span>Official Verification Portal</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight text-white">
                    {{ $settings->verification_title ?? 'Certificate Verification Portal' }}
                </h1>

                <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                    Instantly verify the authenticity of certificates issued by <span class="text-white font-semibold">{{ $settings->institution_name ?? 'our institution' }}</span>.
                </p>

                <!-- Search Box -->
                <div class="verification-search-box">
                    <form onsubmit="event.preventDefault(); window.location.href='/verify/' + this.token.value;" class="flex w-full gap-2">
                        <input type="text" name="token" placeholder="Enter Verification Token (e.g. 8F4K92M7XQ)" required>
                        <button type="submit">Verify Now</button>
                    </form>
                </div>
                <p class="text-slate-400 text-sm mt-4 flex items-center justify-center gap-2">
                    <span class="text-green-400">●</span> Securely verified against official records
                </p>
            </div>
        </section>

        <!-- Feature Section -->
        <section class="py-24 px-4 bg-white">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16 space-y-4">
                    <h2 class="text-3xl font-bold text-slate-900">How it Works</h2>
                    <div class="h-1 w-20 bg-blue-600 mx-auto rounded-full"></div>
                </div>

                <div class="feature-grid">
                    <!-- Step 1 -->
                    <div class="feature-card">
                        <div class="text-4xl mb-4">📱</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Scan QR Code</h3>
                        <p class="text-slate-600 leading-relaxed">Use your smartphone camera to scan the QR code on the physical certificate for instant access.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="feature-card">
                        <div class="text-4xl mb-4">🔑</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Enter Token</h3>
                        <p class="text-slate-600 leading-relaxed">Don't have a scanner? Manually enter the unique verification token found on the certificate.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="feature-card">
                    <div class="text-4xl mb-4">
                        @if($settings && $settings->logo)
                            <img src="{{ Storage::url($settings->logo) }}" class="h-10 w-auto mx-auto" alt="Logo">
                        @else
                            <span>🏛️</span>
                        @endif
                    </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Official Record</h3>
                        <p class="text-slate-600 leading-relaxed">Cross-reference in real-time with our secure, encrypted institutional database.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="mt-auto py-12 px-4 bg-slate-900 text-slate-400 text-center border-t border-slate-800">
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="flex justify-center items-center gap-2 text-white font-semibold">
                    @if($settings && $settings->logo)
                        <img src="{{ Storage::url($settings->logo) }}" class="h-6 w-auto" alt="Logo">
                    @endif
                    <span>{{ $settings->institution_name ?? 'Institution' }}</span>
                </div>
                <p class="text-sm">&copy; {{ date('Y') }} {{ $settings->institution_name ?? 'Institution' }}. All rights reserved.</p>
                <p class="text-xs opacity-60">{{ $settings->verification_footer ?? 'Official record provided for verification purposes only.' }}</p>
            </div>
        </footer>
    </body>
</html>