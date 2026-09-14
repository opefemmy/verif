<x-admin-layout>
    <x-slot name="header">Create New Certificate Record</x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.certificates.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Birth Name</label>
                        <input type="text" name="birth_name" value="{{ old('birth_name') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('birth_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Passport Photograph</label>
                        <input type="file" name="passport_photo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">JPG, JPEG, or PNG. Max 2MB.</p>
                        @error('passport_photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Academic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">School</label>
                        <input type="text" name="school" value="{{ old('school') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('school') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Faculty</label>
                        <input type="text" name="faculty" value="{{ old('faculty') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('faculty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <input type="text" name="department" value="{{ old('department') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Programme</label>
                        <input type="text" name="programme" value="{{ old('programme') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('programme') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('qualification') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Award</label>
                        <input type="text" name="award" value="{{ old('award') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('award') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Class of Award</label>
                        <input type="text" name="class_of_award" value="{{ old('class_of_award') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('class_of_award') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Graduation Date</label>
                        <input type="date" name="graduation_date" value="{{ old('graduation_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('graduation_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Graduation Year</label>
                        <input type="number" name="graduation_year" value="{{ old('graduation_year') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('graduation_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Academic Session</label>
                        <input type="text" name="academic_session" value="{{ old('academic_session') }}" placeholder="e.g. 2024/2025" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('academic_session') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Certificate Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Matriculation Number *</label>
                        <input type="text" name="matric_number" value="{{ old('matric_number') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        @error('matric_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Certificate Number *</label>
                        <input type="text" name="certificate_number" value="{{ old('certificate_number') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        @error('certificate_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="VALID" {{ old('status') == 'VALID' ? 'selected' : '' }}>Valid</option>
                            <option value="REVOKED" {{ old('status') == 'REVOKED' ? 'selected' : '' }}>Revoked</option>
                            <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="ARCHIVED" {{ old('status') == 'ARCHIVED' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Remarks</label>
                        <input type="text" name="remarks" value="{{ old('remarks') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('remarks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.certificates.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold hover:bg-gray-300 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Create Record & Generate QR
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>