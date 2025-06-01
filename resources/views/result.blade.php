@extends('layouts.app')

@section('title', 'Hasil Kelulusan - ' . $student->nama)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
@endsection
