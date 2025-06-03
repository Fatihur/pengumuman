<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default signature location settings
        $defaultSettings = [
            [
                'key' => 'signature_location',
                'value' => 'Jakarta',
                'description' => 'Lokasi untuk tanda tangan di surat kelulusan'
            ],
            [
                'key' => 'signature_date_format',
                'value' => 'auto',
                'description' => 'Format tanggal tanda tangan: auto (tanggal sekarang), custom (tanggal khusus), atau graduation_date (tanggal kelulusan)'
            ],
            [
                'key' => 'signature_custom_date',
                'value' => '',
                'description' => 'Tanggal khusus untuk tanda tangan (jika menggunakan custom date)'
            ]
        ];

        foreach ($defaultSettings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove signature location settings
        DB::table('settings')->whereIn('key', [
            'signature_location',
            'signature_date_format',
            'signature_custom_date'
        ])->delete();
    }
};
