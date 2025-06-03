@extends('layouts.app')

@section('title', 'Data Siswa - Admin Panel')

@section('content')
<div class="admin-container max-w-7xl mx-auto">
    <!-- Header -->
    <div class="admin-header flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Data Siswa</h1>
            <p class="text-gray-600 text-sm md:text-base">Kelola informasi siswa dan status kelulusan</p>
        </div>
        <div class="header-actions flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary text-xs md:text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hide-mobile">Dashboard</span>
            </a>
            <a href="{{ route('admin.students.create') }}" class="admin-btn admin-btn-primary text-xs md:text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="hide-mobile">Tambah Siswa</span>
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

    <!-- Statistics Cards -->
    <div class="stats-grid grid gap-6 mb-8">
        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-blue-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Total Siswa</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-gray-900">{{ $students->total() }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-green-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Lulus</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-green-600">{{ $students->where('status_kelulusan', 'lulus')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-red-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Tidak Lulus</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-red-600">{{ $students->where('status_kelulusan', 'tidak_lulus')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg card-shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Pencarian & Filter</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.students') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                        <input type="text"
                               name="search"
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Nama, NISN, NIS, Kelas..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="tidak_lulus" {{ request('status') === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                        </select>
                    </div>

                    <!-- Kelas Filter -->
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="kelas" id="kelas" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasOptions as $kelas)
                                <option value="{{ $kelas }}" {{ request('kelas') === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Program Studi Filter -->
                    <div>
                        <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                        <select name="program_studi" id="program_studi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Program</option>
                            @foreach($programStudiOptions as $program)
                                <option value="{{ $program }}" {{ request('program_studi') === $program ? 'selected' : '' }}>{{ $program }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>

                    <a href="{{ route('admin.students') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-300">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </a>

                    <a href="{{ route('admin.students.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-300">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export CSV
                    </a>

                    <a href="{{ route('admin.students.import') }}"
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition duration-300">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Import Excel
                    </a>

                    <!-- Per Page -->
                    <div class="flex items-center space-x-2">
                        <label for="per_page" class="text-sm text-gray-600">Tampilkan:</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()" class="px-2 py-1 border border-gray-300 rounded text-sm">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 || !request('per_page') ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg card-shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Siswa</h2>
                <p class="text-sm text-gray-600">
                    Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} data
                </p>
            </div>

            <!-- Bulk Actions -->
            <div class="flex items-center space-x-3">
                <div id="bulk-actions" class="hidden">
                    <form id="bulk-form" method="POST" action="{{ route('admin.students.bulk-action') }}" class="flex items-center space-x-2">
                        @csrf
                        <select name="action" id="bulk-action-select" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="">Pilih Aksi</option>
                            <option value="update_status">Ubah Status</option>
                            <option value="export">Export Terpilih</option>
                            <option value="delete">Hapus Terpilih</option>
                        </select>

                        <div id="status-select" class="hidden">
                            <select name="new_status" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="lulus">Lulus</option>
                                <option value="tidak_lulus">Tidak Lulus</option>
                            </select>
                        </div>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition duration-300">
                            Jalankan
                        </button>
                    </form>
                </div>

                <span id="selected-count" class="text-sm text-gray-600 hidden">
                    <span id="count">0</span> dipilih
                </span>
            </div>
        </div>

        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama', 'sort_order' => request('sort_by') === 'nama' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                   class="flex items-center hover:text-gray-700">
                                    Siswa
                                    @if(request('sort_by') === 'nama')
                                        @if(request('sort_order') === 'asc')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nisn', 'sort_order' => request('sort_by') === 'nisn' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                   class="flex items-center hover:text-gray-700">
                                    NISN/NIS
                                    @if(request('sort_by') === 'nisn')
                                        @if(request('sort_order') === 'asc')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'kelas', 'sort_order' => request('sort_by') === 'kelas' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                   class="flex items-center hover:text-gray-700">
                                    Kelas
                                    @if(request('sort_by') === 'kelas')
                                        @if(request('sort_order') === 'asc')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status_kelulusan', 'sort_order' => request('sort_by') === 'status_kelulusan' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                   class="flex items-center hover:text-gray-700">
                                    Status
                                    @if(request('sort_by') === 'status_kelulusan')
                                        @if(request('sort_order') === 'asc')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->program_studi }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">NISN: {{ $student->nisn }}</div>
                                    <div class="text-sm text-gray-500">NIS: {{ $student->nis }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->kelas }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->tanggal_lahir->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->status_kelulusan === 'lulus')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            LULUS
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            TIDAK LULUS
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.students.edit', $student) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        
                                        @if($student->isLulus())
                                            <a href="{{ route('generate.pdf', $student) }}" class="text-green-600 hover:text-green-900" title="Download PDF">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} data</span>
                    </div>

                    <div class="flex items-center space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($students->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                                ← Sebelumnya
                            </span>
                        @else
                            <a href="{{ $students->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition duration-300">
                                ← Sebelumnya
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                            @if ($page == $students->currentPage())
                                <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition duration-300">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition duration-300">
                                Selanjutnya →
                            </a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                                Selanjutnya →
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Halaman {{ $students->currentPage() }} dari {{ $students->lastPage() }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data siswa</h3>
                <p class="text-gray-500 mb-4">Mulai dengan menambahkan data siswa pertama</p>
                <a href="{{ route('admin.students.create') }}" class="btn-primary text-white px-4 py-2 rounded-md hover:shadow-lg transition duration-300">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Siswa Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const countSpan = document.getElementById('count');
    const bulkActionSelect = document.getElementById('bulk-action-select');
    const statusSelect = document.getElementById('status-select');
    const bulkForm = document.getElementById('bulk-form');

    // Handle select all checkbox
    selectAllCheckbox.addEventListener('change', function() {
        studentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Handle individual checkboxes
    studentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateBulkActions();
        });
    });

    // Handle bulk action select
    bulkActionSelect.addEventListener('change', function() {
        if (this.value === 'update_status') {
            statusSelect.classList.remove('hidden');
        } else {
            statusSelect.classList.add('hidden');
        }
    });

    // Handle bulk form submission
    bulkForm.addEventListener('submit', function(e) {
        const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
        const action = bulkActionSelect.value;

        if (selectedCheckboxes.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu siswa untuk melakukan aksi bulk.');
            return;
        }

        if (!action) {
            e.preventDefault();
            alert('Pilih aksi yang akan dilakukan.');
            return;
        }

        // Add selected student IDs to form
        selectedCheckboxes.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'student_ids[]';
            hiddenInput.value = checkbox.value;
            this.appendChild(hiddenInput);
        });

        // Confirmation for delete action
        if (action === 'delete') {
            if (!confirm(`Apakah Anda yakin ingin menghapus ${selectedCheckboxes.length} data siswa? Aksi ini tidak dapat dibatalkan.`)) {
                e.preventDefault();
                return;
            }
        }

        // Confirmation for status update
        if (action === 'update_status') {
            const newStatus = document.querySelector('select[name="new_status"]').value;
            const statusText = newStatus === 'lulus' ? 'LULUS' : 'TIDAK LULUS';
            if (!confirm(`Apakah Anda yakin ingin mengubah status ${selectedCheckboxes.length} siswa menjadi ${statusText}?`)) {
                e.preventDefault();
                return;
            }
        }
    });

    function updateSelectAllState() {
        const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
        const totalCount = studentCheckboxes.length;

        if (checkedCount === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedCount === totalCount) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }
    }

    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;

        if (checkedCount > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.classList.remove('hidden');
            countSpan.textContent = checkedCount;
        } else {
            bulkActions.classList.add('hidden');
            selectedCount.classList.add('hidden');
            bulkActionSelect.value = '';
            statusSelect.classList.add('hidden');
        }
    }

    // Initialize state
    updateSelectAllState();
    updateBulkActions();
});
</script>
@endsection
