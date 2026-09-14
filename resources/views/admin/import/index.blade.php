<x-admin-layout>
    <x-slot name="header">Bulk Data Import</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg p-8 border border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                <div class="text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800">Import Graduate Records</h2>
                    <p class="text-gray-500 mt-1">Upload an Excel (.xlsx) or CSV file to bulk create certificate records.</p>
                </div>
                <a href="{{ route('admin.import.template') }}" class="px-4 py-2 bg-slate-800 text-white rounded-lg font-semibold hover:bg-slate-700 transition-colors shadow-sm flex items-center gap-2 text-sm">
                    <span>📥</span> Download Template
                </a>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-10 text-sm text-blue-800 space-y-3">
                <p class="font-bold">Important Import Guidelines:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>File format must be .xlsx or .csv.</li>
                    <li>The first row must contain the headers.</li>
                    <li>Required headers: <span class="font-mono font-bold">Full Name, Matric Number, Certificate Number, Programme</span>.</li>
                    <li>To import photos: Upload a .zip file containing images named after the matric numbers (e.g. <span class="font-mono">HND-CS-2024-0012.jpg</span>).</li>
                </ul>
            </div>

            <form action="{{ route('admin.import.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data File Upload -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Graduate Data File (.xlsx, .csv) *</label>
                        <div class="relative group">
                            <input type="file" name="import_file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                        </div>
                    </div>

                    <!-- Passport ZIP Upload -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Passport Photos ZIP (Optional)</label>
                        <div class="relative group">
                            <input type="file" name="passport_zip" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                        <span>👁️</span> Preview Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>