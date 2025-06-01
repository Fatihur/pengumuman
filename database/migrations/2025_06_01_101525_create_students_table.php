<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique(); // NISN siswa
            $table->string('nis')->unique(); // NIS siswa
            $table->string('nama'); // Nama lengkap siswa
            $table->date('tanggal_lahir'); // Tanggal lahir untuk validasi
            $table->string('kelas'); // Kelas siswa
            $table->string('program_studi'); // Program studi/jurusan
            $table->enum('status_kelulusan', ['lulus', 'tidak_lulus'])->default('tidak_lulus');
            $table->text('pesan_khusus')->nullable(); // Pesan dari sekolah
            $table->string('no_surat')->nullable(); // Nomor surat kelulusan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
