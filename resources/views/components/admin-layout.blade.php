<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'CVP Admin') }}</title>
        @if($institutionSettings && $institutionSettings->logo)
            <link rel="icon" href="{{ Storage::url($institutionSettings->logo) }}?v={{ time() }}">
        @endif

        <!-- Tailwind CDN for immediate styling -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Inter', sans-serif; }
            .sidebar-active {
                background-color: #1e293b;
                color: white !important;
            }
        </style>
    </head>
    <body class="bg-slate-100 font-sans text-slate-900 min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:flex flex-col sticky top-0 h-screen">
            <div class="p-6 text-2xl font-extrabold border-b border-slate-800 flex items-center gap-3">
                @if($institutionSettings && $institutionSettings->logo)
                    <img src="{{ Storage::url($institutionSettings->logo) }}" alt="Logo" class="h-10 w-auto object-contain">
                @else
                    <span class="text-3xl">🏛️</span>
                @endif
                <span class="tracking-tight">CVP Admin</span>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'text-slate-400' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.certificates.index') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.certificates.*') ? 'sidebar-active' : 'text-slate-400' }}">
                    <span class="mr-3">🎓</span> Certificates
                </a>
                <a href="{{ route('admin.import.index') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.import.*') ? 'sidebar-active' : 'text-slate-400' }}">
                    <span class="mr-3">📥</span> Import Data
                </a>
                <a href="{{ route('admin.qr-codes.index') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.qr-codes.*') ? 'sidebar-active' : 'text-slate-400' }}">
                    <span class="mr-3">🖼️</span> QR Codes
                </a>
                <a href="{{ route('admin.verification-logs.index') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.verification-logs.*') ? 'sidebar-active' : 'text-slate-400' }}">
                    <span class="mr-3">📜</span> Verification Logs
                </a>
                <div class="pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">System</div>
                <a href="{{ route('admin.settings.edit') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.settings.*') ? 'sidebar-active' : 'text-slate-400' }}">
                    <span class="mr-3">⚙️</span> Institution Settings
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('profile.*') ? 'sidebar-active' : 'text-slate-400' }}">
                    <span class="mr-3">👤</span> Admin Profile
                </a>
            </nav>
            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-3 rounded-lg transition-colors hover:bg-red-900 text-slate-400 hover:text-white">
                        <span class="mr-3">🚪</span> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 sticky top-0 z-10">
                <div class="flex items-center gap-2">
                                            @if($institutionSettings && $institutionSettings->logo)
                            <img src="{{ Storage::url($institutionSettings->logo) }}" alt="Logo" class="h-8 w-auto object-contain">
                        @else
                            <span class="text-xl">🏛️</span>
                        @endif
                    <h2 class="text-lg font-semibold text-gray-800">{{ $header ?? 'Admin Dashboard' }}</h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
                </div>
            </header>
            <main class="p-8 flex-1">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg">
                        {{ session('error') }}
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </body>
</html>