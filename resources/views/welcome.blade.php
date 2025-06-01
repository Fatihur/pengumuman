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

            <!-- Skeleton Loading Form (Hidden by default) -->
            <div id="skeleton-form" class="max-w-md mx-auto hidden">
                <div class="mb-6">
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                </div>
                <div class="mb-6">
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                </div>
                <div class="h-12 bg-gray-200 rounded animate-pulse"></div>
            </div>

            <!-- Checking Loading Overlay -->
            <div id="checking-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 text-center">
                    <div class="relative mb-6">
                        <!-- Animated Search Icon -->
                        <div class="w-20 h-20 mx-auto mb-4 relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full animate-spin-slow"></div>
                            <div class="absolute inset-2 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Floating Dots -->
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Checking Text with Typewriter Effect -->
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Sedang Memeriksa Data</h3>
                        <p class="text-gray-600">
                            <span id="checking-text">Mencari data siswa</span>
                            <span class="animate-pulse">...</span>
                        </p>
                    </div>

                    <!-- Progress Steps -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                            <span>Memulai</span>
                            <span>Memverifikasi</span>
                            <span>Selesai</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="checking-progress" class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Checking Steps -->
                    <div class="text-sm text-gray-500">
                        <div id="checking-step" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menghubungkan ke database...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actual Form -->
            <form id="graduation-form" method="POST" action="{{ route('check.graduation') }}" class="max-w-md mx-auto">
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
                    id="submit-btn"
                    class="w-full btn-primary text-white font-bold py-3 px-4 rounded-md hover:shadow-lg transition duration-300"
                >
                    <span id="btn-text">Cek Hasil Kelulusan</span>
                    <span id="btn-loading" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const graduationForm = document.getElementById('graduation-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoading = document.getElementById('btn-loading');

    // Checking overlay elements
    const checkingOverlay = document.getElementById('checking-overlay');
    const checkingProgress = document.getElementById('checking-progress');
    const checkingText = document.getElementById('checking-text');
    const checkingStep = document.getElementById('checking-step');

    // Checking messages
    const checkingMessages = [
        'Mencari data siswa',
        'Memverifikasi identitas',
        'Mengecek status kelulusan',
        'Menyiapkan hasil',
        'Menyelesaikan proses'
    ];

    const checkingSteps = [
        { icon: 'database', text: 'Menghubungkan ke database...' },
        { icon: 'search', text: 'Mencari data siswa...' },
        { icon: 'shield', text: 'Memverifikasi identitas...' },
        { icon: 'check', text: 'Mengecek status kelulusan...' },
        { icon: 'document', text: 'Menyiapkan hasil...' }
    ];

    // Show checking loading
    function showCheckingLoading() {
        checkingOverlay.classList.remove('hidden');

        let progress = 0;
        let messageIndex = 0;
        let stepIndex = 0;

        const interval = setInterval(() => {
            progress += Math.random() * 15 + 10;

            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                // Form will submit naturally after this
                return;
            }

            checkingProgress.style.width = progress + '%';

            // Update checking message
            if (messageIndex < checkingMessages.length - 1 && progress > (messageIndex + 1) * 20) {
                messageIndex++;
                checkingText.textContent = checkingMessages[messageIndex];
            }

            // Update checking step
            if (stepIndex < checkingSteps.length - 1 && progress > (stepIndex + 1) * 20) {
                stepIndex++;
                const step = checkingSteps[stepIndex];

                let iconSvg = '';
                switch(step.icon) {
                    case 'database':
                        iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>';
                        break;
                    case 'search':
                        iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>';
                        break;
                    case 'shield':
                        iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>';
                        break;
                    case 'check':
                        iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                        break;
                    case 'document':
                        iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>';
                        break;
                }

                checkingStep.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        ${iconSvg}
                    </svg>
                    ${step.text}
                `;
            }
        }, 400);
    }

    // Form submission loading
    if (graduationForm) {
        graduationForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent immediate submission

            // Show button loading state
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            // Add pulse effect to form
            graduationForm.style.transform = 'scale(0.98)';
            graduationForm.style.opacity = '0.8';

            // Show checking loading overlay
            setTimeout(() => {
                showCheckingLoading();
            }, 300);

            // Submit form after loading animation (2-3 seconds)
            setTimeout(() => {
                // Actually submit the form
                const formData = new FormData(graduationForm);
                const submitButton = document.createElement('input');
                submitButton.type = 'hidden';
                submitButton.name = 'submitted';
                submitButton.value = '1';
                graduationForm.appendChild(submitButton);
                graduationForm.submit();
            }, 2500);
        });
    }


});
</script>

<style>
/* Minimal CSS for checking overlay */
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin-slow {
    animation: spin-slow 3s linear infinite;
}
</style>
@endsection
