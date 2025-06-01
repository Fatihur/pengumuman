@extends('layouts.app')

@section('title', 'Pengumuman Belum Tersedia')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 flex items-center justify-center px-4">
    <div class="max-w-2xl w-full">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <!-- School Logo -->
            @php
                $schoolLogo = App\Models\Setting::getValue('school_logo', '');
                $governmentLogo = App\Models\Setting::getValue('government_logo', '');
            @endphp

            <div class="flex justify-center items-center space-x-8 mb-8">
                @if($governmentLogo && file_exists(public_path('storage/' . $governmentLogo)))
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $governmentLogo) }}" alt="Logo Pemerintah" class="h-20 w-20 object-contain">
                    </div>
                @endif
                
                @if($schoolLogo && file_exists(public_path('storage/' . $schoolLogo)))
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $schoolLogo) }}" alt="Logo Sekolah" class="h-20 w-20 object-contain">
                    </div>
                @else
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- School Info -->
            @php
                $governmentName = App\Models\Setting::getValue('government_name', 'PEMERINTAH PROVINSI DKI JAKARTA');
                $departmentName = App\Models\Setting::getValue('department_name', 'DINAS PENDIDIKAN');
                $schoolName = App\Models\Setting::getValue('school_name', 'SMA Negeri 1');
            @endphp
            
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $governmentName }}</h1>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ $departmentName }}</h2>
                <h3 class="text-3xl font-bold text-blue-600">{{ $schoolName }}</h3>
            </div>
        </div>

        <!-- Not Published Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 text-center">
            <!-- Warning Icon -->
            <div class="w-20 h-20 mx-auto bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>

            <!-- Title -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Pengumuman Belum Tersedia</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-orange-400 mx-auto rounded-full"></div>
            </div>

            <!-- Message -->
            <div class="mb-8">
                <p class="text-gray-600 text-lg leading-relaxed max-w-2xl mx-auto mb-6">
                    Pengumuman kelulusan belum dipublikasikan oleh pihak sekolah. 
                    Silakan cek kembali secara berkala atau hubungi pihak sekolah untuk informasi lebih lanjut.
                </p>
            </div>

            <!-- Information Box -->
            <div class="bg-blue-50 rounded-xl p-6 mb-8">
                <div class="flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-blue-800">Informasi</h3>
                </div>
                <div class="text-blue-700 space-y-2">
                    <p>• Pengumuman akan tersedia setelah dipublikasikan oleh admin sekolah</p>
                    <p>• Pastikan untuk mengecek kembali secara berkala</p>
                    <p>• Siapkan NISN dan tanggal lahir Anda untuk pengecekan nanti</p>
                </div>
            </div>

            <!-- Contact Information -->
            @php
                $schoolPhone = App\Models\Setting::getValue('school_phone', '');
                $schoolEmail = App\Models\Setting::getValue('school_email', '');
                $schoolAddress = App\Models\Setting::getValue('school_address', '');
            @endphp

            @if($schoolPhone || $schoolEmail || $schoolAddress)
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kontak Sekolah</h3>
                <div class="space-y-3 text-gray-600">
                    @if($schoolPhone)
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ $schoolPhone }}</span>
                    </div>
                    @endif

                    @if($schoolEmail)
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $schoolEmail }}</span>
                    </div>
                    @endif

                    @if($schoolAddress)
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $schoolAddress }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Refresh Button -->
            <div class="mt-8">
                <button onclick="window.location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh Halaman
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-gray-500 text-sm">© {{ date('Y') }} {{ $schoolName }}. Semua hak dilindungi.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh every 30 seconds
    setInterval(function() {
        // Add a subtle indicator that page is checking for updates
        const refreshBtn = document.querySelector('button[onclick="window.location.reload()"]');
        if (refreshBtn) {
            const originalText = refreshBtn.innerHTML;
            refreshBtn.innerHTML = `
                <svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Mengecek update...
            `;
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    }, 30000); // Check every 30 seconds
});
</script>
@endsection
