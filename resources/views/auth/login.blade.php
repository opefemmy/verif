<x-guest-layout>
    <div class="space-y-6">
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                <input id="email" class="block w-full px-4 py-3 rounded-xl border-slate-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-slate-900" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@institution.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <input id="password" class="block w-full px-4 py-3 rounded-xl border-slate-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-slate-900" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-slate-600">Remember me</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0">
                    Sign In to Dashboard
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>