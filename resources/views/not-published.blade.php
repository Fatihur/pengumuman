@extends('layouts.app')

@section('title', 'Pengumuman Belum Tersedia')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12 lg:mb-16">
            <!-- School Logo -->
            @php
                $schoolLogo = App\Models\Setting::getValue('school_logo', '');
                $governmentLogo = App\Models\Setting::getValue('government_logo', '');
            @endphp

            <!-- Logo Container - Responsive -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-6 lg:gap-8 mb-6 sm:mb-8">
                @if($governmentLogo && file_exists(public_path('storage/' . $governmentLogo)))
                    <div class="flex-shrink-0 transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('storage/' . $governmentLogo) }}"
                             alt="Logo Pemerintah"
                             class="h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 object-contain drop-shadow-lg">
                    </div>
                @endif

                @if($schoolLogo && file_exists(public_path('storage/' . $schoolLogo)))
                    <div class="flex-shrink-0 transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('storage/' . $schoolLogo) }}"
                             alt="Logo Sekolah"
                             class="h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 object-contain drop-shadow-lg">
                    </div>
                @else
                    <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gradient-to-br from-blue-500 via-blue-600 to-purple-600 rounded-full flex items-center justify-center shadow-xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- School Info - Responsive Typography -->
            @php
                $governmentName = App\Models\Setting::getValue('government_name', 'PEMERINTAH PROVINSI DKI JAKARTA');
                $departmentName = App\Models\Setting::getValue('department_name', 'DINAS PENDIDIKAN');
                $schoolName = App\Models\Setting::getValue('school_name', 'SMA Negeri 1');
            @endphp

            <div class="space-y-2 sm:space-y-3">
                <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800 leading-tight">{{ $governmentName }}</h1>
                <h2 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-700">{{ $departmentName }}</h2>
                <h3 class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">{{ $schoolName }}</h3>
            </div>
        </div>

        <!-- Not Published Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 p-6 sm:p-8 lg:p-12 text-center relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-purple-50/50 rounded-3xl"></div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-200/20 to-purple-200/20 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-yellow-200/20 to-orange-200/20 rounded-full translate-y-12 -translate-x-12"></div>

            <!-- Content -->
            <div class="relative z-10">
                <!-- Warning Icon -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 mx-auto bg-gradient-to-br from-amber-100 to-orange-100 rounded-full flex items-center justify-center mb-6 sm:mb-8 shadow-lg transform hover:scale-105 transition-all duration-300">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>

                <!-- Title -->
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold text-gray-800 mb-4 leading-tight">
                        Pengumuman Belum Tersedia
                    </h1>
                    <div class="w-16 sm:w-20 lg:w-24 h-1 bg-gradient-to-r from-amber-400 via-orange-400 to-red-400 mx-auto rounded-full"></div>
                </div>

                <!-- Message -->
                <div class="mb-6 sm:mb-8">
                    <p class="text-gray-600 text-base sm:text-lg lg:text-xl leading-relaxed max-w-3xl mx-auto px-4">
                        Pengumuman kelulusan belum dipublikasikan oleh pihak sekolah.
                        Silakan cek kembali secara berkala atau hubungi pihak sekolah untuk informasi lebih lanjut.
                    </p>
                </div>
            </div>
        </div>

        <!-- Information Box -->
        <div class="mt-6 sm:mt-8 lg:mt-12">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 sm:p-8 border border-blue-100 shadow-lg">
                <div class="flex flex-col sm:flex-row items-center justify-center mb-4 sm:mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-3 sm:mb-0 sm:mr-4 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-blue-800">Informasi Penting</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-blue-700">
                    <div class="flex items-start space-x-3 p-3 bg-white/50 rounded-lg">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-sm sm:text-base">Pengumuman akan tersedia setelah dipublikasikan oleh admin sekolah</p>
                    </div>
                    <div class="flex items-start space-x-3 p-3 bg-white/50 rounded-lg">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-sm sm:text-base">Pastikan untuk mengecek kembali secara berkala</p>
                    </div>
                    <div class="flex items-start space-x-3 p-3 bg-white/50 rounded-lg sm:col-span-2 lg:col-span-1">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-sm sm:text-base">Siapkan NISN dan tanggal lahir Anda untuk pengecekan nanti</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        @php
            $schoolPhone = App\Models\Setting::getValue('school_phone', '');
            $schoolEmail = App\Models\Setting::getValue('school_email', '');
            $schoolAddress = App\Models\Setting::getValue('school_address', '');
        @endphp

        @if($schoolPhone || $schoolEmail || $schoolAddress)
        <div class="mt-6 sm:mt-8">
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-lg">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-600 to-slate-700 rounded-full flex items-center justify-center mr-4 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800">Kontak Sekolah</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if($schoolPhone)
                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Telepon</p>
                            <p class="text-sm sm:text-base font-medium text-gray-800">{{ $schoolPhone }}</p>
                        </div>
                    </div>
                    @endif

                    @if($schoolEmail)
                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                            <p class="text-sm sm:text-base font-medium text-gray-800 break-all">{{ $schoolEmail }}</p>
                        </div>
                    </div>
                    @endif

                    @if($schoolAddress)
                    <div class="flex items-start p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 sm:col-span-2 lg:col-span-1">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Alamat</p>
                            <p class="text-sm sm:text-base font-medium text-gray-800 leading-relaxed">{{ $schoolAddress }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-8 sm:mt-12 flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="window.location.reload()"
                    class="group bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 sm:px-8 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center space-x-2 w-full sm:w-auto justify-center">
                <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Refresh Halaman</span>
            </button>

            <button onclick="history.back()"
                    class="group bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 sm:px-8 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center space-x-2 w-full sm:w-auto justify-center">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </button>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 sm:mt-12">
            <div class="inline-flex items-center space-x-2 text-gray-500 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>© {{ date('Y') }} {{ $schoolName }}. Semua hak dilindungi.</span>
            </div>
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
