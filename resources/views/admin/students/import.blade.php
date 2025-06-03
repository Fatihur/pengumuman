@extends('layouts.app')

@section('title', 'Import Data Siswa')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Import Data Siswa</h1>
            <p class="text-gray-600 mt-2">Upload file Excel atau CSV untuk import data siswa secara bulk</p>
        </div>
        <a href="{{ route('admin.students') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-300">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
            <div class="flex">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="font-bold mb-2">Detail Error Import:</div>
            <div class="max-h-40 overflow-y-auto">
                @foreach(session('import_errors') as $error)
                    <div class="text-sm">• {{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Import Form -->
        <div class="bg-white rounded-lg card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Upload File</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.students.import.process') }}" method="POST" enctype="multipart/form-data" id="import-form">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih File Excel/CSV
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-300">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="excel_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file</span>
                                        <input id="excel_file" name="excel_file" type="file" class="sr-only" accept=".xlsx,.xls,.csv" required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Excel (.xlsx, .xls) atau CSV hingga 10MB
                                </p>
                            </div>
                        </div>
                        @error('excel_file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium">Tips Import:</p>
                                    <ul class="mt-1 list-disc list-inside space-y-1">
                                        <li>Pastikan format file sesuai template</li>
                                        <li>NISN harus unik dan tidak boleh kosong</li>
                                        <li>Data yang sudah ada akan dilewati</li>
                                        <li>Format tanggal: YYYY-MM-DD atau DD/MM/YYYY</li>
                                        <li><strong>Nomor surat akan di-generate otomatis</strong> - tidak perlu diisi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Template & Instructions -->
        <div class="space-y-6">
            <!-- Download Template -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Template Excel</h2>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Template Excel (.xlsx)</p>
                                <p class="text-xs text-blue-600">Format Excel dengan styling dan petunjuk lengkap</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Download template Excel dengan format yang sudah disesuaikan, termasuk contoh data dan petunjuk pengisian.
                    </p>
                    <a href="{{ route('admin.students.template') }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-300 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Template Excel
                    </a>
                </div>
            </div>

            <!-- Format Data -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Format Data</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-900">NISN:</span>
                            <span class="text-gray-600">Nomor unik siswa (wajib)</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">NIS:</span>
                            <span class="text-gray-600">Nomor induk siswa</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Nama:</span>
                            <span class="text-gray-600">Nama lengkap siswa (wajib)</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Tanggal Lahir:</span>
                            <span class="text-gray-600">Format: YYYY-MM-DD</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Kelas:</span>
                            <span class="text-gray-600">Contoh: XII IPA 1</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Program Studi:</span>
                            <span class="text-gray-600">Contoh: IPA, IPS, Bahasa</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Status Kelulusan:</span>
                            <span class="text-gray-600">lulus / tidak_lulus</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Pesan Khusus:</span>
                            <span class="text-gray-600">Opsional</span>
                        </div>
                        <div class="bg-green-50 p-3 rounded-md border border-green-200">
                            <span class="font-medium text-green-800">📝 Nomor Surat:</span>
                            <span class="text-green-700">Akan di-generate otomatis oleh sistem untuk siswa yang lulus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('excel_file');
    const form = document.getElementById('import-form');
    
    // Handle file selection display
    fileInput.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            const label = document.querySelector('label[for="excel_file"] span');
            label.textContent = fileName;
        }
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengimport...
        `;
    });
});
</script>
@endsection
