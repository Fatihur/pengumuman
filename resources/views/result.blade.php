@extends('layouts.app')

@section('title', 'Hasil Kelulusan - ' . $student->nama)

@section('content')
<!-- Result Loading Overlay -->
<div id="result-loading" class="fixed inset-0 bg-white bg-opacity-95 flex items-center justify-center z-50">
    <div class="text-center">
        <div class="relative mb-6">
            <!-- Neutral Result Icon Animation -->
            <div class="w-24 h-24 mx-auto mb-4 relative">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full animate-pulse"></div>
                <div class="absolute inset-3 bg-white rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-blue-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Neutral floating particles -->
            <div class="absolute inset-0 pointer-events-none">
                @for($i = 0; $i < 6; $i++)
                    <div class="absolute w-2 h-2 bg-blue-400 rounded-full animate-ping"
                         style="top: {{ rand(10, 80) }}%; left: {{ rand(10, 80) }}%; animation-delay: {{ $i * 0.2 }}s;"></div>
                @endfor
            </div>
        </div>

        <!-- Loading Text -->
        <div class="text-xl font-bold text-gray-800 mb-2">
            <span id="result-text">Memuat hasil kelulusan</span>
            <span class="animate-pulse">...</span>
        </div>

        <!-- Student Name Preview -->
        <div class="text-lg text-gray-600 mb-4">
            {{ $student->nama }}
        </div>

        <!-- Neutral Progress Bar -->
        <div class="w-80 bg-gray-200 rounded-full h-3 mx-auto">
            <div id="result-progress" class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
        </div>
    </div>
</div>

<!-- Page Content with Animation -->
<div id="result-content" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 opacity-0 transform translate-y-8 transition-all duration-1000 ease-out">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Halaman Utama
        </a>
    </div>

    <!-- Result Card -->
    <div class="bg-white rounded-lg card-shadow overflow-hidden">
        <!-- Header with Status -->
        <div class="px-8 py-6 {{ $student->isLulus() ? 'bg-green-50 border-b border-green-200' : 'bg-red-50 border-b border-red-200' }}">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center {{ $student->isLulus() ? 'bg-green-100' : 'bg-red-100' }}">
                    @if($student->isLulus())
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </div>
                
                <h1 class="text-3xl font-bold {{ $student->isLulus() ? 'text-green-800' : 'text-red-800' }} mb-2">
                    {{ $student->isLulus() ? 'SELAMAT!' : 'MOHON MAAF' }}
                </h1>
                
                <p class="text-xl {{ $student->isLulus() ? 'text-green-700' : 'text-red-700' }}">
                    Anda dinyatakan <strong>{{ strtoupper($student->status_kelulusan) }}</strong>
                </p>
            </div>
        </div>

        <!-- Student Information -->
        <div class="px-8 py-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Siswa</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->nama }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">NISN</label>
                    <p class="text-lg text-gray-900">{{ $student->nisn }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">NIS</label>
                    <p class="text-lg text-gray-900">{{ $student->nis }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Kelas</label>
                    <p class="text-lg text-gray-900">{{ $student->kelas }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Program Studi</label>
                    <p class="text-lg text-gray-900">{{ $student->program_studi }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                    <p class="text-lg text-gray-900">{{ $student->tanggal_lahir->format('d F Y') }}</p>
                </div>
            </div>

            @if($student->pesan_khusus)
                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Pesan Khusus</h3>
                    <p class="text-blue-800">{{ $student->pesan_khusus }}</p>
                </div>
            @endif

            @if($student->isLulus())
                <div class="mt-8 text-center">
                    <a 
                        href="{{ route('generate.pdf', $student->id) }}" 
                        class="inline-flex items-center btn-primary text-white font-bold py-3 px-6 rounded-md hover:shadow-lg transition duration-300"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Unduh Surat Kelulusan
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Additional Information -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Selanjutnya</h3>
        
        @if($student->isLulus())
            <div class="space-y-3 text-gray-700">
                <p class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Selamat atas kelulusan Anda! Silakan unduh surat keterangan kelulusan sebagai bukti resmi.
                </p>
                <p class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Untuk informasi lebih lanjut mengenai prosedur selanjutnya, silakan hubungi pihak sekolah.
                </p>
            </div>
        @else
            <div class="space-y-3 text-gray-700">
                <p class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Mohon maaf, Anda belum memenuhi syarat kelulusan pada periode ini.
                </p>
                <p class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Silakan hubungi pihak sekolah untuk informasi mengenai langkah selanjutnya.
                </p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resultLoading = document.getElementById('result-loading');
    const resultContent = document.getElementById('result-content');
    const resultProgress = document.getElementById('result-progress');
    const resultText = document.getElementById('result-text');

    const isLulus = {{ $student->isLulus() ? 'true' : 'false' }};

    // Neutral result loading messages (same for both lulus and tidak lulus)
    const resultMessages = [
        'Memuat hasil kelulusan',
        'Memverifikasi data siswa',
        'Menyiapkan informasi',
        'Memproses hasil akhir',
        'Menampilkan hasil'
    ];

    // Show result loading
    function showResultLoading() {
        let progress = 0;
        let messageIndex = 0;

        const interval = setInterval(() => {
            progress += Math.random() * 20 + 15;

            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                hideResultLoading();
            }

            resultProgress.style.width = progress + '%';

            // Update result message
            if (messageIndex < resultMessages.length - 1 && progress > (messageIndex + 1) * 20) {
                messageIndex++;
                resultText.textContent = resultMessages[messageIndex];

                // Keep neutral styling throughout loading
                // No color changes or special effects that give away the result
            }
        }, 300);
    }

    // Hide result loading and show content
    function hideResultLoading() {
        setTimeout(() => {
            resultLoading.style.opacity = '0';
            resultLoading.style.transform = 'scale(0.95)';

            setTimeout(() => {
                resultLoading.style.display = 'none';
                resultContent.style.opacity = '1';
                resultContent.style.transform = 'translateY(0)';

                // Add celebration effect for lulus
                if (isLulus) {
                    createCelebration();
                }

                // Animate elements
                animateResultElements();
            }, 300);
        }, 800);
    }

    // Create celebration effect for lulus
    function createCelebration() {
        const colors = ['#10B981', '#34D399', '#6EE7B7', '#A7F3D0'];

        for (let i = 0; i < 50; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = '50%';
                confetti.style.pointerEvents = 'none';
                confetti.style.zIndex = '1000';
                confetti.style.animation = 'fall 3s linear forwards';

                document.body.appendChild(confetti);

                setTimeout(() => {
                    confetti.remove();
                }, 3000);
            }, i * 50);
        }
    }

    // Animate result elements
    function animateResultElements() {
        const elements = resultContent.querySelectorAll('.bg-white, .bg-gray-50, .mb-6');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';

            setTimeout(() => {
                element.style.transition = 'all 0.6s ease-out';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });

        // Special animation for status icon
        const statusIcon = resultContent.querySelector('.w-20.h-20');
        if (statusIcon) {
            setTimeout(() => {
                statusIcon.style.animation = isLulus ? 'bounce 1s ease-in-out 3' : 'shake 0.5s ease-in-out 2';
            }, 1000);
        }

        // Animate download button if exists
        const downloadBtn = resultContent.querySelector('a[href*="generate.pdf"]');
        if (downloadBtn) {
            setTimeout(() => {
                downloadBtn.style.animation = 'pulse-glow 2s infinite';
                downloadBtn.classList.add('pulse-blue');
            }, 2000);
        }
    }

    // Add hover effects to download button
    const downloadBtn = document.querySelector('a[href*="generate.pdf"]');
    if (downloadBtn) {
        downloadBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) translateY(-2px)';
            this.style.boxShadow = '0 10px 25px rgba(59, 130, 246, 0.3)';
        });

        downloadBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) translateY(0)';
            this.style.boxShadow = '';
        });

        downloadBtn.addEventListener('click', function() {
            // Add download animation
            this.style.animation = 'pulse 0.3s ease-in-out';

            // Show download feedback
            const originalText = this.innerHTML;
            this.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyiapkan PDF...
            `;

            setTimeout(() => {
                this.innerHTML = originalText;
            }, 2000);
        });
    }

    // Start result loading
    showResultLoading();
});
</script>

<style>
/* Additional animations for result page */
@keyframes fall {
    0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(360deg);
        opacity: 0;
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Removed gradient text animation to keep loading neutral */

/* Pulse glow animation */
@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
    }
    50% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
    }
}

.pulse-blue {
    animation: pulse-glow 2s infinite;
}

/* Enhanced transitions */
.transition-all {
    transition: all 0.3s ease-out;
}
</style>
@endsection
