@extends('layouts.app')

@section('title', 'Pengumuman Kelulusan Online - ' . $schoolName)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <div class="mb-8">
            <!-- School Logo -->
            @php
                $schoolLogo = App\Models\Setting::getValue('school_logo', '');
            @endphp

            @if($schoolLogo && file_exists(public_path('storage/' . $schoolLogo)))
                <div class="w-24 h-24 mx-auto mb-4">
                    <img src="{{ asset('storage/' . $schoolLogo) }}" alt="Logo Sekolah" class="w-full h-full object-contain rounded-full border-2 border-gray-200">
                </div>
            @else
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
            @endif
        </div>
        
        @php
            $schoolName = App\Models\Setting::getValue('school_name', 'SMA Negeri 1');
        @endphp
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $schoolName }}</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-2">Pengumuman Kelulusan</h2>
        <p class="text-xl text-gray-600">Tahun Ajaran {{ $graduationYear }}</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
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

    <!-- Main Card -->
    <div class="bg-white rounded-lg card-shadow p-8 mb-8">
        @if($isPublished)
            <!-- Graduation Check Form -->
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Cek Hasil Kelulusan</h3>
                <p class="text-gray-600 mb-6">Masukkan NISN/NIS dan tanggal lahir Anda untuk melihat hasil kelulusan</p>
            </div>

            <form method="POST" action="{{ route('check.graduation') }}" class="max-w-md mx-auto">
                @csrf
                <div class="mb-6">
                    <label for="nisn_nis" class="block text-sm font-medium text-gray-700 mb-2">
                        NISN / NIS
                    </label>
                    <input 
                        type="text" 
                        id="nisn_nis" 
                        name="nisn_nis" 
                        value="{{ old('nisn_nis') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan NISN atau NIS"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Lahir
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

                <button 
                    type="submit" 
                    class="w-full btn-primary text-white font-bold py-3 px-4 rounded-md hover:shadow-lg transition duration-300"
                >
                    Cek Hasil Kelulusan
                </button>
            </form>
        @else
            <!-- Not Published Message -->
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pengumuman Belum Tersedia</h3>
                <p class="text-gray-600">Pengumuman kelulusan belum dipublikasikan. Silakan cek kembali nanti.</p>
            </div>
        @endif
    </div>

    <!-- Information Section -->
    <div class="bg-blue-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-blue-900 mb-3">Informasi Penting:</h4>
        <ul class="text-blue-800 space-y-2">
            <li class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Pastikan NISN/NIS dan tanggal lahir yang dimasukkan sudah benar
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Siswa yang lulus dapat mengunduh surat keterangan kelulusan
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Jika mengalami kesulitan, hubungi pihak sekolah
            </li>
        </ul>
    </div>
</div>
@endsection
