@extends('layouts.app')

@section('title', 'Pengaturan - Admin Panel')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pengaturan Sistem</h1>
                <p class="text-gray-600">Kelola konfigurasi sekolah dan sistem pengumuman</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-300">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Settings Form -->
    <div class="bg-white rounded-lg card-shadow">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Government Information Section -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Pemerintah</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="government_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pemerintah <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="government_name"
                            name="government_name"
                            value="{{ old('government_name', $settings['government_name']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: PEMERINTAH PROVINSI DKI JAKARTA"
                            required
                        >
                    </div>

                    <div>
                        <label for="department_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Dinas <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="department_name"
                            name="department_name"
                            value="{{ old('department_name', $settings['department_name']) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: DINAS PENDIDIKAN"
                            required
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="government_logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Logo Pemerintah
                        </label>
                        <input
                            type="file"
                            id="government_logo"
                            name="government_logo"
                            accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB. Logo akan ditampilkan di sebelah kiri header.</p>
                        @if($settings['government_logo'])
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['government_logo']) }}" alt="Logo Pemerintah" class="h-16 w-16 object-cover rounded">
                                <p class="text-sm text-gray-600">Logo pemerintah saat ini</p>
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
@endsection
