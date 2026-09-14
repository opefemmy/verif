<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CVP Admin Login') }}</title>
        @if($institutionSettings && $institutionSettings->logo)
            <link rel="icon" href="{{ Storage::url($institutionSettings->logo) }}?v={{ time() }}">
        @endif

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen flex items-center justify-center p-6">
        <x-splash />

        <div class="w-full max-w-md">
            <!-- Branding -->
            <div class="text-center mb-8">
                @if($institutionSettings && $institutionSettings->logo)
                    <img src="{{ Storage::url($institutionSettings->logo) }}" class="h-24 w-auto mx-auto mb-4" alt="Logo">
                @else
                    <span class="text-6xl block mb-4">🏛️</span>
                @endif
                <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ $institutionSettings->institution_name ?? 'Institution' }}</h1>
                <p class="text-slate-400 mt-2">Secure Administrative Access</p>
            </div>

            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-slate-200">
                <div class="p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>