@extends('layouts.app')

@section('title', 'Pengaturan - Admin Panel')

@section('content')
<div class="admin-container max-w-4xl mx-auto">
    <!-- Header -->
    <div class="admin-header flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Pengaturan Sistem</h1>
            <p class="text-gray-600 text-sm md:text-base">Kelola konfigurasi sekolah dan sistem pengumuman</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary text-xs md:text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hide-mobile">Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="admin-alert admin-alert-success">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="admin-alert admin-alert-error">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Settings Form -->
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Government Information Section -->
            <div class="admin-card-header">
                <h2 class="text-lg md:text-xl font-semibold text-gray-900">Informasi Pemerintah</h2>
            </div>
            <div class="admin-card-body border-b border-gray-200">
                <div class="admin-form-grid grid gap-6">
                    <div>
                        <label for="government_name" class="admin-label">
                            Nama Pemerintah <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="government_name"
                            name="government_name"
                            value="{{ old('government_name', $settings['government_name']) }}"
                            class="admin-input"
                            placeholder="Contoh: PEMERINTAH PROVINSI DKI JAKARTA"
                            required
                        >
                    </div>

                    <div>
                        <label for="department_name" class="admin-label">
                            Nama Dinas <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="department_name"
                            name="department_name"
                            value="{{ old('department_name', $settings['department_name']) }}"
                            class="admin-input"
                            placeholder="Contoh: DINAS PENDIDIKAN"
                            required
                        >
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <label for="government_logo" class="admin-label">
                            Logo Pemerintah
                        </label>
                        <input
                            type="file"
                            id="government_logo"
                            name="government_logo"
                            accept="image/*"
                            class="admin-input"
                        >
                        <p class="text-xs md:text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB. Logo akan ditampilkan di sebelah kiri header.</p>
                        @if($settings['government_logo'])
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['government_logo']) }}" alt="Logo Pemerintah" class="h-12 w-12 md:h-16 md:w-16 object-cover rounded">
                                <p class="text-xs md:text-sm text-gray-600">Logo pemerintah saat ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- School Information Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Sekolah</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="school_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="school_name"
                            name="school_name"
                            value="{{ old('school_name', $settings['school_name']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: SMA Negeri 1 Jakarta"
                            required
                        >
                    </div>

                    <div>
                        <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Kelulusan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="graduation_year"
                            name="graduation_year"
                            value="{{ old('graduation_year', $settings['graduation_year']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="2025"
                            min="2020"
                            max="2030"
                            required
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="school_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Sekolah <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="school_address"
                            name="school_address"
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Jl. Pendidikan No. 123, Jakarta Pusat, DKI Jakarta 10110"
                            required
                        >{{ old('school_address', $settings['school_address']) }}</textarea>
                    </div>

                    <div>
                        <label for="school_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input
                            type="text"
                            id="school_phone"
                            name="school_phone"
                            value="{{ old('school_phone', $settings['school_phone']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: (021) 1234567"
                        >
                    </div>

                    <div>
                        <label for="school_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Sekolah
                        </label>
                        <input
                            type="email"
                            id="school_email"
                            name="school_email"
                            value="{{ old('school_email', $settings['school_email']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: info@sekolah.com"
                        >
                    </div>

                    <div>
                        <label for="school_website" class="block text-sm font-medium text-gray-700 mb-2">
                            Website Sekolah
                        </label>
                        <input
                            type="text"
                            id="school_website"
                            name="school_website"
                            value="{{ old('school_website', $settings['school_website']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: www.sekolah.com"
                        >
                    </div>

                    <div>
                        <label for="school_logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Logo Sekolah
                        </label>
                        <input
                            type="file"
                            id="school_logo"
                            name="school_logo"
                            accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB. Logo akan ditampilkan di sebelah kanan header.</p>
                        @if($settings['school_logo'])
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['school_logo']) }}" alt="Logo Sekolah" class="h-16 w-16 object-cover rounded">
                                <p class="text-sm text-gray-600">Logo sekolah saat ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Principal Information Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Kepala Sekolah</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="principal_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kepala Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="principal_name"
                            name="principal_name"
                            value="{{ old('principal_name', $settings['principal_name']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Dr. Soekarno, M.Pd"
                            required
                        >
                    </div>

                    <div>
                        <label for="principal_nip" class="block text-sm font-medium text-gray-700 mb-2">
                            NIP Kepala Sekolah
                        </label>
                        <input
                            type="text"
                            id="principal_nip"
                            name="principal_nip"
                            value="{{ old('principal_nip', $settings['principal_nip']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: 196501011990031001"
                        >
                    </div>
                </div>
            </div>

            <!-- Signature Settings Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Pengaturan Tanda Tangan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Signature Location -->
                    <div>
                        <label for="signature_location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi Tanda Tangan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="signature_location"
                            name="signature_location"
                            value="{{ old('signature_location', $settings['signature_location'] ?? 'Jakarta') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Jakarta, Bandung, Surabaya"
                            required
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Lokasi yang akan ditampilkan di tanda tangan surat kelulusan
                        </p>
                    </div>

                    <!-- Signature Date Format -->
                    <div>
                        <label for="signature_date_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Format Tanggal Tanda Tangan <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="signature_date_format"
                            name="signature_date_format"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                            onchange="toggleCustomDateField()"
                        >
                            <option value="auto" {{ old('signature_date_format', $settings['signature_date_format'] ?? 'auto') == 'auto' ? 'selected' : '' }}>
                                Tanggal Sekarang (Otomatis)
                            </option>
                            <option value="custom" {{ old('signature_date_format', $settings['signature_date_format'] ?? 'auto') == 'custom' ? 'selected' : '' }}>
                                Tanggal Khusus
                            </option>
                            <option value="graduation_date" {{ old('signature_date_format', $settings['signature_date_format'] ?? 'auto') == 'graduation_date' ? 'selected' : '' }}>
                                Tanggal Input Siswa
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Pilih format tanggal yang akan digunakan untuk tanda tangan
                        </p>
                    </div>

                    <!-- Custom Date Field -->
                    <div id="custom_date_field" class="md:col-span-2" style="display: {{ old('signature_date_format', $settings['signature_date_format'] ?? 'auto') == 'custom' ? 'block' : 'none' }};">
                        <label for="signature_custom_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Khusus
                        </label>
                        <input
                            type="date"
                            id="signature_custom_date"
                            name="signature_custom_date"
                            value="{{ old('signature_custom_date', $settings['signature_custom_date'] ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Tanggal khusus yang akan digunakan untuk semua surat kelulusan
                        </p>
                    </div>

                    <!-- Preview -->
                    <div class="md:col-span-2 bg-blue-50 border border-blue-200 rounded-md p-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Preview Tanda Tangan:</h4>
                        <div class="text-sm text-blue-700" id="signature_preview">
                            <span id="preview_location">{{ $settings['signature_location'] ?? 'Jakarta' }}</span>,
                            <span id="preview_date">
                                @if(($settings['signature_date_format'] ?? 'auto') == 'custom' && !empty($settings['signature_custom_date']))
                                    {{ \App\Helpers\IndonesianDate::formatMedium($settings['signature_custom_date']) }}
                                @else
                                    {{ \App\Helpers\IndonesianDate::formatMedium(now()) }}
                                @endif
                            </span>
                            <br>Kepala Sekolah
                        </div>
                    </div>
                </div>
            </div>

            <!-- SK Settings Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Pengaturan Surat Keterangan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Format Nomor Surat -->
                    <div>
                        <label for="sk_number_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Format Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="sk_number_format"
                               id="sk_number_format"
                               value="{{ old('sk_number_format', $settings['sk_number_format'] ?? 'SK/{counter}/{year}') }}"
                               placeholder="SK/{counter}/{year}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <p class="text-xs text-gray-500 mt-1">
                            Gunakan: {counter} = nomor urut, {year} = tahun, {month} = bulan, {student_id} = ID siswa
                        </p>
                    </div>

                    <!-- Counter Start -->
                    <div>
                        <label for="sk_counter_start" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Urut Mulai Dari
                        </label>
                        <input type="number"
                               name="sk_counter_start"
                               id="sk_counter_start"
                               value="{{ old('sk_counter_start', $settings['sk_counter_start'] ?? 1) }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Nomor urut akan dimulai dari angka ini</p>
                    </div>

                    <!-- Reset Counter Yearly -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="sk_reset_yearly"
                                   value="1"
                                   {{ old('sk_reset_yearly', $settings['sk_reset_yearly'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Reset nomor urut setiap tahun</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Nomor urut akan kembali ke 1 setiap tahun baru</p>
                    </div>

                    <!-- Auto Generate Number -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="sk_auto_generate"
                                   value="1"
                                   {{ old('sk_auto_generate', $settings['sk_auto_generate'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Generate nomor surat otomatis</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Nomor surat akan dibuat otomatis saat cetak sertifikat</p>
                    </div>

                    <!-- SK Title -->
                    <div class="md:col-span-2">
                        <label for="sk_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Surat Keterangan
                        </label>
                        <input type="text"
                               name="sk_title"
                               id="sk_title"
                               value="{{ old('sk_title', $settings['sk_title'] ?? 'SURAT KETERANGAN KELULUSAN') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- SK Opening Text -->
                    <div class="md:col-span-2">
                        <label for="sk_opening_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Teks Pembuka
                        </label>
                        <textarea name="sk_opening_text"
                                  id="sk_opening_text"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Yang bertanda tangan di bawah ini, Kepala {school_name}, dengan ini menerangkan bahwa:">{{ old('sk_opening_text', $settings['sk_opening_text'] ?? 'Yang bertanda tangan di bawah ini, Kepala {school_name}, dengan ini menerangkan bahwa:') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Gunakan: {school_name} = nama sekolah, {principal_name} = nama kepala sekolah
                        </p>
                    </div>

                    <!-- SK Closing Text -->
                    <div class="md:col-span-2">
                        <label for="sk_closing_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Teks Penutup
                        </label>
                        <textarea name="sk_closing_text"
                                  id="sk_closing_text"
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.">{{ old('sk_closing_text', $settings['sk_closing_text'] ?? 'Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.') }}</textarea>
                    </div>

                    <!-- Graduation Statement -->
                    <div class="md:col-span-2">
                        <label for="sk_graduation_statement" class="block text-sm font-medium text-gray-700 mb-2">
                            Pernyataan Kelulusan
                        </label>
                        <textarea name="sk_graduation_statement"
                                  id="sk_graduation_statement"
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Telah LULUS dari {school_name} pada Tahun Pelajaran {prev_year}/{graduation_year}">{{ old('sk_graduation_statement', $settings['sk_graduation_statement'] ?? 'Telah LULUS dari {school_name} pada Tahun Pelajaran {prev_year}/{graduation_year}') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Gunakan: {school_name} = nama sekolah, {graduation_year} = tahun kelulusan, {prev_year} = tahun sebelumnya
                        </p>
                    </div>

                    <!-- Show Photo Box -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="sk_show_photo"
                                   value="1"
                                   {{ old('sk_show_photo', $settings['sk_show_photo'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Tampilkan kotak foto 3x4</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Menampilkan area untuk tempel foto siswa</p>
                    </div>

                    <!-- Show QR Code -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="sk_show_qr"
                                   value="1"
                                   {{ old('sk_show_qr', $settings['sk_show_qr'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Tampilkan QR Code verifikasi</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">QR Code untuk verifikasi keaslian surat</p>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-md font-medium text-gray-900 mb-4">Preview Format Nomor Surat</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="text-sm text-gray-600 mb-2">Contoh nomor surat yang akan dihasilkan:</div>
                        <div class="font-mono text-lg text-blue-600" id="sk-preview">
                            SK/001/2024
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publication Settings Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Pengaturan Publikasi</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="mr-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Publikasikan Pengumuman</h3>
                                <p class="text-sm text-gray-600">
                                    Aktifkan untuk menampilkan pengumuman kelulusan kepada siswa
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="is_published" 
                                    value="1"
                                    {{ $settings['is_published'] ? 'checked' : '' }}
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800">Perhatian</h4>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Jika publikasi dinonaktifkan, siswa tidak akan dapat melihat hasil kelulusan mereka. 
                                    Pastikan semua data siswa sudah benar sebelum mengaktifkan publikasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Sistem</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Laravel Version</p>
                                <p class="text-lg font-bold text-blue-800">{{ app()->version() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-green-900">PHP Version</p>
                                <p class="text-lg font-bold text-green-800">{{ PHP_VERSION }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-purple-900">Last Updated</p>
                                <p class="text-lg font-bold text-purple-800">{{ now()->format('d/m/Y') }}</p>
                            </div>
                        </div>
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
                        <a href="{{ route('admin.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-md transition duration-300">
                            Batal
                        </a>
                        <button 
                            type="submit" 
                            class="btn-primary text-white font-bold py-2 px-6 rounded-md hover:shadow-lg transition duration-300"
                        >
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg card-shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Preview Halaman Publik</p>
                    <p class="text-sm text-gray-500">Lihat tampilan untuk siswa</p>
                </div>
            </a>
            
            <a href="{{ route('admin.students') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Kelola Data Siswa</p>
                    <p class="text-sm text-gray-500">Tambah, edit, atau hapus data siswa</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formatInput = document.getElementById('sk_number_format');
    const counterInput = document.getElementById('sk_counter_start');
    const preview = document.getElementById('sk-preview');

    function updatePreview() {
        let format = formatInput.value || 'SK/{counter}/{year}';
        let counter = counterInput.value || 1;
        let year = new Date().getFullYear();
        let month = String(new Date().getMonth() + 1).padStart(2, '0');

        // Replace placeholders
        let preview_text = format
            .replace('{counter}', String(counter).padStart(3, '0'))
            .replace('{year}', year)
            .replace('{month}', month)
            .replace('{student_id}', '123');

        preview.textContent = preview_text;
    }

    formatInput.addEventListener('input', updatePreview);
    counterInput.addEventListener('input', updatePreview);

    // Initial preview
    updatePreview();
});

// Signature settings functions
function toggleCustomDateField() {
    const dateFormat = document.getElementById('signature_date_format').value;
    const customDateField = document.getElementById('custom_date_field');

    if (dateFormat === 'custom') {
        customDateField.style.display = 'block';
    } else {
        customDateField.style.display = 'none';
    }

    updateSignaturePreview();
}

function updateSignaturePreview() {
    const location = document.getElementById('signature_location').value || 'Jakarta';
    const dateFormat = document.getElementById('signature_date_format').value;
    const customDate = document.getElementById('signature_custom_date').value;

    const previewLocation = document.getElementById('preview_location');
    const previewDate = document.getElementById('preview_date');

    previewLocation.textContent = location;

    let dateText = '';
    const today = new Date();
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    if (dateFormat === 'custom' && customDate) {
        const date = new Date(customDate);
        dateText = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    } else {
        dateText = `${today.getDate()} ${months[today.getMonth()]} ${today.getFullYear()}`;
    }

    previewDate.textContent = dateText;
}

// Add event listeners for signature settings
document.addEventListener('DOMContentLoaded', function() {
    const locationInput = document.getElementById('signature_location');
    const dateFormatSelect = document.getElementById('signature_date_format');
    const customDateInput = document.getElementById('signature_custom_date');

    if (locationInput) {
        locationInput.addEventListener('input', updateSignaturePreview);
    }

    if (dateFormatSelect) {
        dateFormatSelect.addEventListener('change', function() {
            toggleCustomDateField();
            updateSignaturePreview();
        });
    }

    if (customDateInput) {
        customDateInput.addEventListener('change', updateSignaturePreview);
    }

    // Initial signature preview
    updateSignaturePreview();
});
</script>
@endsection
