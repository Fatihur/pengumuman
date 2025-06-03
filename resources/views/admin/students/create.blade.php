Class "Maatwebsite\Excel\Facades\Excel" not found
@extends('layouts.app')

@section('title', 'Tambah Siswa - Admin Panel')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Siswa Baru</h1>
                <p class="text-gray-600">Masukkan informasi siswa dan status kelulusan</p>
            </div>
            <a href="{{ route('admin.students') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-300">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if($errors->any())
        <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <h4 class="font-bold mb-2">Terdapat kesalahan:</h4>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg card-shadow">
        <form method="POST" action="{{ route('admin.students.store') }}">
            @csrf
            
            <!-- Personal Information Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Pribadi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            value="{{ old('nama') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nama lengkap siswa"
                            required
                        >
                    </div>

                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                            NISN <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nisn"
                            name="nisn"
                            value="{{ old('nisn') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nisn') border-red-500 @enderror"
                            placeholder="Nomor Induk Siswa Nasional (10 digit angka)"
                            maxlength="10"
                            pattern="[0-9]{10}"
                            title="NISN harus berupa 10 digit angka"
                            inputmode="numeric"
                            required
                        >
                        @error('nisn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">NISN harus berupa 10 digit angka</p>
                    </div>

                    <div>
                        <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">
                            NIS <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nis"
                            name="nis"
                            value="{{ old('nis') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nis') border-red-500 @enderror"
                            placeholder="Nomor Induk Siswa (angka)"
                            pattern="[0-9]+"
                            title="NIS harus berupa angka"
                            inputmode="numeric"
                            required
                        >
                        @error('nis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">NIS harus berupa angka</p>
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="tanggal_lahir" 
                            name="tanggal_lahir" 
                            value="{{ old('tanggal_lahir') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                    </div>

                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="kelas" 
                            name="kelas" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">Pilih Kelas</option>
                            <option value="XII IPA 1" {{ old('kelas') == 'XII IPA 1' ? 'selected' : '' }}>XII IPA 1</option>
                            <option value="XII IPA 2" {{ old('kelas') == 'XII IPA 2' ? 'selected' : '' }}>XII IPA 2</option>
                            <option value="XII IPA 3" {{ old('kelas') == 'XII IPA 3' ? 'selected' : '' }}>XII IPA 3</option>
                            <option value="XII IPS 1" {{ old('kelas') == 'XII IPS 1' ? 'selected' : '' }}>XII IPS 1</option>
                            <option value="XII IPS 2" {{ old('kelas') == 'XII IPS 2' ? 'selected' : '' }}>XII IPS 2</option>
                            <option value="XII IPS 3" {{ old('kelas') == 'XII IPS 3' ? 'selected' : '' }}>XII IPS 3</option>
                            <option value="XII Bahasa" {{ old('kelas') == 'XII Bahasa' ? 'selected' : '' }}>XII Bahasa</option>
                        </select>
                    </div>

                    <div>
                        <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-2">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="program_studi" 
                            name="program_studi" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">Pilih Program Studi</option>
                            <option value="IPA" {{ old('program_studi') == 'IPA' ? 'selected' : '' }}>IPA (Ilmu Pengetahuan Alam)</option>
                            <option value="IPS" {{ old('program_studi') == 'IPS' ? 'selected' : '' }}>IPS (Ilmu Pengetahuan Sosial)</option>
                            <option value="Bahasa" {{ old('program_studi') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Graduation Status Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Status Kelulusan</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Status Kelulusan <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input 
                                    type="radio" 
                                    name="status_kelulusan" 
                                    value="lulus" 
                                    {{ old('status_kelulusan') == 'lulus' ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                                    required
                                >
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">LULUS</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Siswa dinyatakan lulus dan dapat mengunduh surat kelulusan</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input 
                                    type="radio" 
                                    name="status_kelulusan" 
                                    value="tidak_lulus" 
                                    {{ old('status_kelulusan') == 'tidak_lulus' ? 'checked' : '' }}
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                                    required
                                >
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">TIDAK LULUS</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Siswa belum memenuhi syarat kelulusan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="no_surat" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Surat Kelulusan
                        </label>
                        <input 
                            type="text" 
                            id="no_surat" 
                            name="no_surat" 
                            value="{{ old('no_surat') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: SK/001/2025 (opsional, akan dibuat otomatis jika kosong)"
                        >
                        <p class="text-sm text-gray-500 mt-1">Kosongkan untuk membuat nomor surat otomatis</p>
                    </div>

                    <div>
                        <label for="pesan_khusus" class="block text-sm font-medium text-gray-700 mb-2">
                            Pesan Khusus
                        </label>
                        <textarea 
                            id="pesan_khusus" 
                            name="pesan_khusus" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Pesan khusus dari sekolah untuk siswa (opsional)"
                        >{{ old('pesan_khusus') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Pesan ini akan ditampilkan di hasil kelulusan dan surat keterangan</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <span class="text-red-500">*</span> Field wajib diisi
                    </div>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.students') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-md transition duration-300">
                            Batal
                        </a>
                        <button 
                            type="submit" 
                            class="btn-primary text-white font-bold py-2 px-6 rounded-md hover:shadow-lg transition duration-300"
                        >
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Data Siswa
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-sync program studi with kelas selection
        document.getElementById('kelas').addEventListener('change', function() {
            const kelas = this.value;
            const programStudi = document.getElementById('program_studi');

            if (kelas.includes('IPA')) {
                programStudi.value = 'IPA';
            } else if (kelas.includes('IPS')) {
                programStudi.value = 'IPS';
            } else if (kelas.includes('Bahasa')) {
                programStudi.value = 'Bahasa';
            }
        });

        // Numeric validation for NISN and NIS
        function setupNumericValidation(elementId, maxLength = null) {
            const element = document.getElementById(elementId);

            // Only allow numeric input
            element.addEventListener('input', function(e) {
                // Remove any non-numeric characters
                let value = e.target.value.replace(/[^0-9]/g, '');

                // Apply max length if specified
                if (maxLength && value.length > maxLength) {
                    value = value.substring(0, maxLength);
                }

                e.target.value = value;

                // Visual feedback
                if (value.length > 0 && !/^[0-9]+$/.test(value)) {
                    e.target.classList.add('border-red-500');
                    e.target.classList.remove('border-gray-300');
                } else {
                    e.target.classList.remove('border-red-500');
                    e.target.classList.add('border-gray-300');
                }
            });

            // Prevent non-numeric keypress
            element.addEventListener('keypress', function(e) {
                // Allow: backspace, delete, tab, escape, enter
                if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                    // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true)) {
                    return;
                }

                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

            // Paste validation
            element.addEventListener('paste', function(e) {
                setTimeout(function() {
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    if (maxLength && value.length > maxLength) {
                        value = value.substring(0, maxLength);
                    }
                    e.target.value = value;
                }, 10);
            });
        }

        // Setup validation for NISN (10 digits) and NIS (no limit)
        setupNumericValidation('nisn', 10);
        setupNumericValidation('nis');

        // NISN specific validation
        document.getElementById('nisn').addEventListener('blur', function() {
            const value = this.value;
            const errorElement = this.parentNode.querySelector('.nisn-error');

            // Remove existing error
            if (errorElement) {
                errorElement.remove();
            }

            if (value.length > 0 && value.length !== 10) {
                const error = document.createElement('p');
                error.className = 'text-red-500 text-sm mt-1 nisn-error';
                error.textContent = 'NISN harus tepat 10 digit angka';
                this.parentNode.appendChild(error);
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });

        // NIS validation
        document.getElementById('nis').addEventListener('blur', function() {
            const value = this.value;
            const errorElement = this.parentNode.querySelector('.nis-error');

            // Remove existing error
            if (errorElement) {
                errorElement.remove();
            }

            if (value.length > 0 && !/^[0-9]+$/.test(value)) {
                const error = document.createElement('p');
                error.className = 'text-red-500 text-sm mt-1 nis-error';
                error.textContent = 'NIS harus berupa angka';
                this.parentNode.appendChild(error);
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });

        // Auto-generate NISN if empty (demo purposes)
        document.getElementById('nama').addEventListener('blur', function() {
            const nisn = document.getElementById('nisn');
            if (!nisn.value) {
                // Generate a random 10-digit NISN for demo
                const randomNISN = Math.floor(1000000000 + Math.random() * 9000000000);
                nisn.value = randomNISN.toString();
            }
        });

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const nisn = document.getElementById('nisn').value;
            const nis = document.getElementById('nis').value;
            let hasError = false;

            // Validate NISN
            if (nisn.length !== 10 || !/^[0-9]{10}$/.test(nisn)) {
                alert('NISN harus berupa 10 digit angka');
                document.getElementById('nisn').focus();
                hasError = true;
            }

            // Validate NIS
            if (nis.length === 0 || !/^[0-9]+$/.test(nis)) {
                alert('NIS harus berupa angka');
                document.getElementById('nis').focus();
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush
@endsection
