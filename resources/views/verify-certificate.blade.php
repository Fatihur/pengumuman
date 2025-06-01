@extends('layouts.app')

@section('title', 'Verifikasi Sertifikat - ' . $student->nama)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Sertifikat Terverifikasi</h1>
        <p class="text-gray-600">Sertifikat kelulusan ini telah diverifikasi sebagai dokumen resmi</p>
    </div>

    <!-- Certificate Verification Card -->
    <div class="bg-white rounded-lg card-shadow overflow-hidden">
        <!-- Header with Status -->
        <div class="px-8 py-6 bg-green-50 border-b border-green-200">
            <div class="flex items-center justify-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-green-800">SERTIFIKAT VALID</h2>
                    <p class="text-green-700">Dokumen ini adalah sertifikat kelulusan resmi</p>
                </div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="px-8 py-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Informasi Siswa</h3>
            
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

            <!-- Graduation Status -->
            <div class="mt-8 p-6 bg-green-50 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                    <div>
                        <h4 class="text-lg font-semibold text-green-900">Status Kelulusan: LULUS</h4>
                        <p class="text-green-800">Siswa telah dinyatakan lulus dari {{ $schoolName }}</p>
                    </div>
                </div>
            </div>

            @if($student->pesan_khusus)
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="text-lg font-semibold text-blue-900 mb-2">Pesan Khusus</h4>
                    <p class="text-blue-800">{{ $student->pesan_khusus }}</p>
                </div>
            @endif
        </div>

        <!-- Verification Details -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Verifikasi</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Sekolah:</span>
                    <span class="text-gray-600 ml-2">{{ $schoolName }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Waktu Verifikasi:</span>
                    <span class="text-gray-600 ml-2">{{ now()->format('d F Y, H:i:s') }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">ID Verifikasi:</span>
                    <span class="text-gray-600 ml-2 font-mono">{{ strtoupper(substr(md5($student->id . $student->nisn), 0, 8)) }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Status Dokumen:</span>
                    <span class="text-green-600 ml-2 font-semibold">✓ Terverifikasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                <h4 class="text-lg font-semibold text-yellow-900 mb-2">Pemberitahuan Keamanan</h4>
                <ul class="text-yellow-800 space-y-1">
                    <li>• Sertifikat ini telah diverifikasi melalui sistem keamanan digital</li>
                    <li>• Setiap sertifikat memiliki kode QR unik yang tidak dapat dipalsukan</li>
                    <li>• Untuk verifikasi lebih lanjut, hubungi {{ $schoolName }}</li>
                    <li>• Dokumen ini sah dan dapat digunakan untuk keperluan resmi</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 text-center">
        <a href="{{ route('home') }}" class="inline-flex items-center btn-primary text-white font-bold py-3 px-6 rounded-md hover:shadow-lg transition duration-300 mr-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Beranda
        </a>
        
        <button onclick="window.print()" class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Halaman
        </button>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .card-shadow {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
    }
</style>
@endpush
@endsection
