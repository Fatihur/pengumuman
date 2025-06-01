<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nisn',
        'nis',
        'nama',
        'tanggal_lahir',
        'kelas',
        'program_studi',
        'status_kelulusan',
        'pesan_khusus',
        'no_surat'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Scope untuk siswa yang lulus
     */
    public function scopeLulus($query)
    {
        return $query->where('status_kelulusan', 'lulus');
    }

    /**
     * Scope untuk siswa yang tidak lulus
     */
    public function scopeTidakLulus($query)
    {
        return $query->where('status_kelulusan', 'tidak_lulus');
    }

    /**
     * Cek apakah siswa lulus
     */
    public function isLulus()
    {
        return $this->status_kelulusan === 'lulus';
    }
}
