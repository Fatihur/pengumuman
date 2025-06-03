<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Carbon\Carbon;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Student::truncate();

        // Data nama siswa Indonesia yang realistis
        $namaDepan = [
            'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gilang', 'Hani', 'Indra', 'Joko',
            'Kartika', 'Lestari', 'Maya', 'Nanda', 'Oka', 'Putri', 'Qori', 'Rina', 'Sari', 'Tono',
            'Umar', 'Vina', 'Wati', 'Xena', 'Yudi', 'Zara', 'Andi', 'Bayu', 'Candra', 'Dian',
            'Eka', 'Fajar', 'Gita', 'Hendra', 'Ika', 'Jihan', 'Kiki', 'Lina', 'Mira', 'Nisa',
            'Oki', 'Prita', 'Qila', 'Reza', 'Sinta', 'Tari', 'Ulfa', 'Vera', 'Winda', 'Yoga'
        ];

        $namaBelakang = [
            'Pratama', 'Sari', 'Putra', 'Putri', 'Wijaya', 'Santoso', 'Lestari', 'Handoko', 'Susanti', 'Kurniawan',
            'Rahayu', 'Setiawan', 'Wulandari', 'Permana', 'Maharani', 'Nugroho', 'Anggraini', 'Saputra', 'Dewi', 'Firmansyah',
            'Safitri', 'Hidayat', 'Puspita', 'Ramadhan', 'Kusuma', 'Indrawati', 'Prasetyo', 'Melati', 'Hakim', 'Salsabila',
            'Adiputra', 'Cahyani', 'Mahendra', 'Kartini', 'Suryana', 'Pertiwi', 'Gunawan', 'Amelia', 'Darmawan', 'Safira',
            'Nugraha', 'Azzahra', 'Pranata', 'Kirana', 'Wardana', 'Ananda', 'Saputri', 'Maulana', 'Ramadhani', 'Syahputra'
        ];

        // Data kelas dan program studi
        $kelasData = [
            ['kelas' => 'XII IPA 1', 'program_studi' => 'IPA'],
            ['kelas' => 'XII IPA 2', 'program_studi' => 'IPA'],
            ['kelas' => 'XII IPA 3', 'program_studi' => 'IPA'],
            ['kelas' => 'XII IPS 1', 'program_studi' => 'IPS'],
            ['kelas' => 'XII IPS 2', 'program_studi' => 'IPS'],
            ['kelas' => 'XII IPS 3', 'program_studi' => 'IPS'],
            ['kelas' => 'XII Bahasa', 'program_studi' => 'Bahasa'],
        ];

        // Pesan khusus untuk siswa lulus
        $pesanLulus = [
            'Selamat! Anda telah berhasil menyelesaikan pendidikan dengan baik.',
            'Prestasi yang membanggakan! Terus semangat untuk meraih cita-cita.',
            'Kerja keras Anda membuahkan hasil. Selamat atas kelulusan ini!',
            'Anda telah menunjukkan dedikasi yang luar biasa. Selamat!',
            'Pencapaian yang luar biasa! Semoga sukses di jenjang selanjutnya.',
            'Selamat atas kelulusan yang gemilang ini!',
            'Prestasi terbaik! Terus berkarya dan berprestasi.',
            'Anda adalah kebanggaan sekolah. Selamat atas kelulusan!',
            'Hasil yang memuaskan! Semoga menjadi bekal untuk masa depan.',
            'Selamat! Anda telah membuktikan kemampuan terbaik Anda.'
        ];

        // Pesan khusus untuk siswa tidak lulus
        $pesanTidakLulus = [
            'Jangan menyerah! Gunakan kesempatan ini untuk belajar lebih giat.',
            'Ini bukan akhir dari segalanya. Tetap semangat untuk mencoba lagi.',
            'Kegagalan adalah awal dari kesuksesan. Terus berusaha!',
            'Evaluasi dan perbaiki kekurangan. Anda pasti bisa!',
            'Jangan patah semangat. Gunakan waktu ini untuk persiapan yang lebih baik.',
            'Setiap orang memiliki waktu yang berbeda untuk sukses. Tetap semangat!',
            'Belajar dari pengalaman ini dan bangkit lebih kuat.',
            'Kesempatan masih terbuka lebar. Jangan menyerah!',
            'Ini adalah pelajaran berharga. Terus berusaha dan berdoa.',
            'Percaya pada kemampuan diri. Anda pasti bisa meraih yang terbaik!'
        ];

        $students = [];
        $usedNISN = [];
        $usedNIS = [];

        for ($i = 1; $i <= 100; $i++) {
            // Generate NISN unik (10 digit)
            do {
                $nisn = '30' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            } while (in_array($nisn, $usedNISN));
            $usedNISN[] = $nisn;

            // Generate NIS unik (5-6 digit)
            do {
                $nis = rand(10000, 999999);
            } while (in_array($nis, $usedNIS));
            $usedNIS[] = $nis;

            // Generate nama
            $namaLengkap = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];

            // Random kelas dan program studi
            $kelasInfo = $kelasData[array_rand($kelasData)];

            // Generate tanggal lahir (umur 17-18 tahun)
            $tanggalLahir = Carbon::now()->subYears(rand(17, 18))->subDays(rand(1, 365));

            // Status kelulusan (85% lulus, 15% tidak lulus)
            $statusKelulusan = rand(1, 100) <= 85 ? 'lulus' : 'tidak_lulus';

            // Pesan khusus berdasarkan status
            if ($statusKelulusan === 'lulus') {
                $pesanKhusus = $pesanLulus[array_rand($pesanLulus)];
            } else {
                $pesanKhusus = $pesanTidakLulus[array_rand($pesanTidakLulus)];
            }

            // Generate nomor surat untuk yang lulus
            $noSurat = null;
            if ($statusKelulusan === 'lulus') {
                $noSurat = 'SK/' . str_pad($i, 3, '0', STR_PAD_LEFT) . '/XII/' . date('Y');
            }

            $students[] = [
                'nisn' => $nisn,
                'nis' => $nis,
                'nama' => $namaLengkap,
                'tanggal_lahir' => $tanggalLahir->format('Y-m-d'),
                'kelas' => $kelasInfo['kelas'],
                'program_studi' => $kelasInfo['program_studi'],
                'status_kelulusan' => $statusKelulusan,
                'pesan_khusus' => $pesanKhusus,
                'no_surat' => $noSurat,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert data dalam batch untuk performa yang lebih baik
        $chunks = array_chunk($students, 25);
        foreach ($chunks as $chunk) {
            Student::insert($chunk);
        }

        $this->command->info('✅ Berhasil membuat 100 data siswa');
        $this->command->info('📊 Distribusi:');
        
        // Hitung distribusi
        $totalLulus = collect($students)->where('status_kelulusan', 'lulus')->count();
        $totalTidakLulus = collect($students)->where('status_kelulusan', 'tidak_lulus')->count();
        
        $this->command->info("   - Siswa Lulus: {$totalLulus}");
        $this->command->info("   - Siswa Tidak Lulus: {$totalTidakLulus}");
        
        // Distribusi per kelas
        $distribusiKelas = collect($students)->groupBy('kelas')->map->count();
        foreach ($distribusiKelas as $kelas => $jumlah) {
            $this->command->info("   - {$kelas}: {$jumlah} siswa");
        }
    }
}
