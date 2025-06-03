<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@sekolah.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Create 100 sample students
        $this->call(StudentsSeeder::class);

        // Create settings
        $settings = [
            ['key' => 'government_name', 'value' => 'PEMERINTAH PROVINSI DKI JAKARTA', 'description' => 'Nama pemerintah'],
            ['key' => 'department_name', 'value' => 'DINAS PENDIDIKAN', 'description' => 'Nama dinas'],
            ['key' => 'government_logo', 'value' => '', 'description' => 'Logo pemerintah'],
            ['key' => 'school_name', 'value' => 'SMA NEGERI 1 JAKARTA', 'description' => 'Nama sekolah'],
            ['key' => 'graduation_year', 'value' => '2025', 'description' => 'Tahun kelulusan'],
            ['key' => 'principal_name', 'value' => 'Dr. Soekarno, M.Pd', 'description' => 'Nama kepala sekolah'],
            ['key' => 'principal_nip', 'value' => '196501011990031001', 'description' => 'NIP kepala sekolah'],
            ['key' => 'school_address', 'value' => 'Jl. Pendidikan No. 123, Jakarta Pusat, DKI Jakarta 10110', 'description' => 'Alamat sekolah'],
            ['key' => 'school_phone', 'value' => '(021) 1234567', 'description' => 'Nomor telepon sekolah'],
            ['key' => 'school_email', 'value' => 'info@sman1jakarta.sch.id', 'description' => 'Email sekolah'],
            ['key' => 'school_website', 'value' => 'www.sman1jakarta.sch.id', 'description' => 'Website sekolah'],
            ['key' => 'school_logo', 'value' => '', 'description' => 'Logo sekolah'],
            ['key' => 'is_published', 'value' => '1', 'description' => 'Status publikasi pengumuman'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'description' => $setting['description']]
            );
        }
    }
}
