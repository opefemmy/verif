<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Certificate Verification Portal') }} - Admin</title>
        @if($institutionSettings && $institutionSettings->logo)
            <link rel="icon" href="{{ Storage::url($institutionSettings->logo) }}?v={{ time() }}">
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-100" x-data="{ mobileMenuOpen: false }">
        <div class="min-h-screen flex">
            <!-- Mobile Header -->
            <div class="md:hidden fixed top-0 left-0 right-0 bg-slate-900 text-white h-16 flex items-center justify-between px-4 z-50">
                <div class="flex items-center gap-2">
                    @if($institutionSettings && $institutionSettings->logo)
                        <img src="{{ Storage::url($institutionSettings->logo) }}" alt="Logo" class="h-8 w-auto object-contain">
                    @else
                        <span class="text-2xl">🏛️</span>
                    @endif
                    <span class="font-bold tracking-tight">CVP Admin</span>
                </div>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg hover:bg-slate-800 focus:outline-none">
                    <span x-show="!mobileMenuOpen" class="text-2xl">☰</span>
                    <span x-show="mobileMenuOpen" class="text-2xl">✕</span>
                </button>
            </div>

            <!-- Sidebar Overlay -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition opacity-ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition opacity-ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileMenuOpen = false"
                 class="fixed inset-0 bg-slate-900/50 z-40 md:hidden"></div>

            <!-- Sidebar -->
            <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-white flex-shrink-0 transition-transform duration-300 ease-in-out z-50 md:relative md:translate-x-0 flex flex-col">
                <div class="p-6 text-2xl font-extrabold border-b border-slate-800 flex items-center gap-3">
                    @if($institutionSettings && $institutionSettings->logo)
                        <img src="{{ Storage::url($institutionSettings->logo) }}" alt="Logo" class="h-10 w-auto object-contain">
                    @else
                        <span class="text-3xl">🏛️</span>
                    @endif
                    <span class="tracking-tight">CVP Admin</span>
                </div>
                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-400' }}">
                        <span class="mr-3">📊</span> Dashboard
                    </a>
                    <a href="{{ route('admin.certificates.index') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.certificates.*') ? 'bg-slate-800 text-white' : 'text-slate-400' }}">
                        <span class="mr-3">🎓</span> Certificates
                    </a>
                    <a href="{{ route('admin.import.index') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.import.*') ? 'bg-slate-800 text-white' : 'text-slate-400' }}">
                        <span class="mr-3">📥</span> Import Data
                    </a>
                    <a href="{{ route('admin.qr-codes.index') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.qr-codes.*') ? 'bg-slate-800 text-white' : 'text-slate-400' }}">
                        <span class="mr-3">🖼️</span> QR Codes
                    </a>
                    <a href="{{ route('admin.verification-logs.index') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.verification-logs.*') ? 'bg-slate-800 text-white' : 'text-slate-400' }}">
                        <span class="mr-3">📜</span> Verification Logs
                    </a>
                    <div class="pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">System</div>
                    <a href="{{ route('admin.settings.edit') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-white' : 'text-slate-400' }}">
                        <span class="mr-3">⚙️</span> Institution Settings
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-slate-800 {{ request()->routeIs('profile.*') ? 'bg-slate-800 text-white' : 'text-slate-400' }}">
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
                <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 mt-16 md:mt-0">
                    <div class="flex items-center gap-2">
                        @if($institutionSettings && $institutionSettings->logo)
                            <img src="{{ Storage::url($institutionSettings->logo) }}" alt="Logo" class="h-8 w-auto object-contain">
                        @else
                            <span class="text-xl">🏛️</span>
                        @endif
                        <h2 class="text-lg font-semibold text-gray-800">@yield('header')</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
                    </div>
                </header>
                <main class="p-4 md:p-8 flex-1">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>