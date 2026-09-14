<x-admin-layout>
    <x-slot name="header">Institution Settings</x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">General Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Institution Name *</label>
                        <input type="text" name="institution_name" value="{{ old('institution_name', $settings->institution_name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('institution_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Short Name</label>
                        <input type="text" name="short_name" value="{{ old('short_name', $settings->short_name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('short_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Motto</label>
                        <input type="text" name="motto" value="{{ old('motto', $settings->motto) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('motto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Branding</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Institution Logo</label>
                        <input type="file" name="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($settings->logo)
                            <div class="mt-2">
                                <img src="{{ Storage::url($settings->logo) }}" class="h-20 w-auto rounded border" alt="Logo">
                            </div>
                        @endif
                        @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Favicon</label>
                        <input type="file" name="favicon" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($settings->favicon)
                            <div class="mt-2">
                                <img src="{{ Storage::url($settings->favicon) }}" class="h-10 w-10 rounded border" alt="Favicon">
                            </div>
                        @endif
                        @error('favicon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Institution Seal</label>
                        <input type="file" name="seal" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($settings->seal)
                            <div class="mt-2">
                                <img src="{{ Storage::url($settings->seal) }}" class="h-20 w-20 rounded-full border" alt="Seal">
                            </div>
                        @endif
                        @error('seal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Contact & Location</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Full Address</label>
                        <textarea name="address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $settings->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" value="{{ old('city', $settings->city) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">State</label>
                        <input type="text" name="state" value="{{ old('state', $settings->state) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Country</label>
                        <input type="text" name="country" value="{{ old('country', $settings->country) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Official Email</label>
                        <input type="email" name="official_email" value="{{ old('official_email', $settings->official_email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('official_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Website URL</label>
                        <input type="url" name="website" value="{{ old('website', $settings->website) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Authorized Signatures</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Registrar Signature (PNG recommended)</label>
                        <input type="file" name="registrar_signature" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($settings->registrar_signature)
                            <div class="mt-2">
                                <img src="{{ Storage::url($settings->registrar_signature) }}" class="h-16 w-auto rounded border bg-gray-50" alt="Registrar Signature">
                            </div>
                        @endif
                        @error('registrar_signature') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Rector Signature (PNG recommended)</label>
                        <input type="file" name="rector_signature" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($settings->rector_signature)
                            <div class="mt-2">
                                <img src="{{ Storage::url($settings->rector_signature) }}" class="h-16 w-auto rounded border bg-gray-50" alt="Rector Signature">
                            </div>
                        @endif
                        @error('rector_signature') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Verification Page Title</label>
                        <input type="text" name="verification_title" value="{{ old('verification_title', $settings->verification_title) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('verification_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Verification Footer Text</label>
                        <textarea name="verification_footer" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('verification_footer', $settings->verification_footer) }}</textarea>
                        @error('verification_footer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>