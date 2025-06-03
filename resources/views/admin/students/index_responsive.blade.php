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
                    <p class="stat-value text-xl md:text-2xl font-bold text-gray-900">{{ $students->total() ?? 0 }}</p>
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
                    <p class="stat-value text-xl md:text-2xl font-bold text-green-600">{{ $students->where('status_kelulusan', 'lulus')->count() ?? 0 }}</p>
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
                    <p class="stat-value text-xl md:text-2xl font-bold text-red-600">{{ $students->where('status_kelulusan', 'tidak_lulus')->count() ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="admin-card mb-6">
        <div class="admin-card-header">
            <h2 class="text-lg md:text-xl font-semibold text-gray-900">Filter & Pencarian</h2>
        </div>
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.students') }}" class="space-y-4">
                <div class="filter-grid grid gap-4">
                    <div>
                        <label class="admin-label">Cari Siswa</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="admin-input" placeholder="Nama, NISN, atau NIS">
                    </div>
                    
                    <div>
                        <label class="admin-label">Status Kelulusan</label>
                        <select name="status" class="admin-select">
                            <option value="">Semua Status</option>
                            <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="tidak_lulus" {{ request('status') == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="admin-label">Kelas</label>
                        <select name="kelas" class="admin-select">
                            <option value="">Semua Kelas</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('kelas') == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="admin-label">Jurusan</label>
                        <select name="jurusan" class="admin-select">
                            <option value="">Semua Jurusan</option>
                            <option value="IPA" {{ request('jurusan') == 'IPA' ? 'selected' : '' }}>IPA</option>
                            <option value="IPS" {{ request('jurusan') == 'IPS' ? 'selected' : '' }}>IPS</option>
                            <option value="Bahasa" {{ request('jurusan') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions flex gap-2 pt-4">
                    <button type="submit" class="admin-btn admin-btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="hide-mobile">Cari</span>
                    </button>
                    
                    <a href="{{ route('admin.students') }}" class="admin-btn admin-btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="hide-mobile">Reset</span>
                    </a>
                    
                    <a href="{{ route('admin.students.import') }}" class="admin-btn admin-btn-success">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        <span class="hide-mobile">Import Excel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if(isset($students) && $students->count() > 0)
    <div class="admin-card mb-6">
        <div class="admin-card-body">
            <div class="bulk-actions flex items-center gap-4">
                <span class="text-sm font-medium text-gray-700">Aksi Massal:</span>
                <form method="POST" action="{{ route('admin.students.bulk-update') }}" class="flex items-center gap-2">
                    @csrf
                    <select name="action" class="admin-select" style="width: auto;">
                        <option value="">Pilih Aksi</option>
                        <option value="lulus">Tandai Lulus</option>
                        <option value="tidak_lulus">Tandai Tidak Lulus</option>
                        <option value="delete">Hapus Terpilih</option>
                    </select>
                    <button type="submit" class="admin-btn admin-btn-warning" onclick="return confirm('Yakin ingin melakukan aksi ini?')">
                        Jalankan
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="text-lg md:text-xl font-semibold text-gray-900">Daftar Siswa</h2>
        </div>
        <div class="admin-card-body p-0">
            <div class="admin-table-container">
                @if(isset($students) && $students->count() > 0)
                <table class="admin-table w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-mobile">NISN/NIS</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-mobile">Kelas</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="rounded">
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700">{{ substr($student->nama, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->nama }}</div>
                                        <div class="text-sm text-gray-500 show-mobile">{{ $student->nisn ?? $student->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hide-mobile">
                                {{ $student->nisn ?? $student->nis }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hide-mobile">
                                {{ $student->kelas ?? '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($student->status_kelulusan == 'lulus')
                                    <span class="admin-badge admin-badge-success">Lulus</span>
                                @elseif($student->status_kelulusan == 'tidak_lulus')
                                    <span class="admin-badge admin-badge-danger">Tidak Lulus</span>
                                @else
                                    <span class="admin-badge admin-badge-warning">Belum Ditentukan</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.students.edit', $student->id) }}" class="admin-btn admin-btn-secondary text-xs">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="hide-mobile">Edit</span>
                                    </a>
                                    
                                    @if($student->status_kelulusan == 'lulus')
                                    <a href="{{ route('admin.students.certificate', $student->id) }}" class="admin-btn admin-btn-success text-xs" target="_blank">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="hide-mobile">PDF</span>
                                    </a>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger text-xs" onclick="return confirm('Yakin ingin menghapus?')">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="hide-mobile">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data siswa</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan siswa baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.students.create') }}" class="admin-btn admin-btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Siswa
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($students) && $students->hasPages())
    <div class="pagination-container flex justify-between items-center mt-6">
        <div class="pagination-info text-sm text-gray-700">
            Menampilkan {{ $students->firstItem() }} sampai {{ $students->lastItem() }} dari {{ $students->total() }} hasil
        </div>
        <div class="pagination-links">
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<script>
// Select all checkbox functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>
@endsection
