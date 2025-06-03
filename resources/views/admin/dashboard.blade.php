@extends('layouts.app')

@section('title', 'Dashboard Admin - Pengumuman Kelulusan')

@section('content')
<div class="admin-container max-w-7xl mx-auto">
    <!-- Header -->
    <div class="admin-header flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-600 text-sm md:text-base">Kelola pengumuman kelulusan siswa</p>
        </div>
        <div class="header-actions flex gap-2">
            <a href="{{ route('home') }}" target="_blank" class="admin-btn admin-btn-secondary text-xs md:text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                <span class="hide-mobile">Lihat Publik</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid grid mb-8">
        <!-- Total Students -->
        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-blue-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Total Siswa</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>

        <!-- Passed Students -->
        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-green-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Lulus</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-green-600">{{ $lulusCount }}</p>
                </div>
            </div>
        </div>

        <!-- Failed Students -->
        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-red-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Tidak Lulus</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-red-600">{{ $tidakLulusCount }}</p>
                </div>
            </div>
        </div>

        <!-- Publication Status -->
        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full {{ $isPublished ? 'bg-green-100' : 'bg-yellow-100' }} flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 {{ $isPublished ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($isPublished)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        @endif
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Status</p>
                    <p class="stat-value text-sm md:text-lg font-bold {{ $isPublished ? 'text-green-600' : 'text-yellow-600' }}">
                        <span class="hide-mobile">{{ $isPublished ? 'Dipublikasi' : 'Belum Dipublikasi' }}</span>
                        <span class="show-mobile">{{ $isPublished ? 'Aktif' : 'Nonaktif' }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-grid grid gap-6 mb-8">
        <!-- Quick Actions Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="text-lg md:text-xl font-semibold text-gray-900">Aksi Cepat</h2>
            </div>
            <div class="admin-card-body">
                <div class="space-y-3">
                    <a href="{{ route('admin.students.create') }}" class="admin-btn admin-btn-primary w-full">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm md:text-base">Tambah Siswa Baru</span>
                    </a>

                    <a href="{{ route('admin.students') }}" class="admin-btn admin-btn-secondary w-full">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <span class="text-sm md:text-base">Kelola Data Siswa</span>
                    </a>

                    <a href="{{ route('admin.settings') }}" class="admin-btn admin-btn-warning w-full">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm md:text-base">Pengaturan Sistem</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="text-lg md:text-xl font-semibold text-gray-900">Informasi Sistem</h2>
            </div>
            <div class="admin-card-body">
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs md:text-sm font-medium text-blue-900">Status Publikasi</p>
                            <p class="text-xs md:text-sm text-blue-700 truncate">
                                {{ $isPublished ? 'Pengumuman sedang dipublikasi' : 'Pengumuman belum dipublikasi' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs md:text-sm font-medium text-green-900">Tingkat Kelulusan</p>
                            <p class="text-xs md:text-sm text-green-700">
                                {{ $totalStudents > 0 ? round(($lulusCount / $totalStudents) * 100, 1) : 0 }}% siswa lulus
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs md:text-sm font-medium text-purple-900">Terakhir Diperbarui</p>
                            <p class="text-xs md:text-sm text-purple-700">{{ now()->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="text-lg md:text-xl font-semibold text-gray-900">Tautan Cepat</h2>
        </div>
        <div class="admin-card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center p-3 md:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm md:text-base font-medium text-gray-900 truncate">Lihat Halaman Publik</p>
                        <p class="text-xs md:text-sm text-gray-500 truncate">Cek tampilan untuk siswa</p>
                    </div>
                </a>

                <a href="{{ route('admin.students') }}" class="flex items-center p-3 md:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm md:text-base font-medium text-gray-900 truncate">Data Siswa</p>
                        <p class="text-xs md:text-sm text-gray-500 truncate">Kelola informasi siswa</p>
                    </div>
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center p-3 md:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                    </svg>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm md:text-base font-medium text-gray-900 truncate">Pengaturan</p>
                        <p class="text-xs md:text-sm text-gray-500 truncate">Konfigurasi sistem</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
