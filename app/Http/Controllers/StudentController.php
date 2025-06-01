<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class StudentController extends Controller
{
    /**
     * Tampilkan halaman utama
     */
    public function index()
    {
        $schoolName = Setting::getValue('school_name', 'SMA Negeri 1');
        $graduationYear = Setting::getValue('graduation_year', date('Y'));
        $isPublished = Setting::getValue('is_published', false);

        return view('welcome', compact('schoolName', 'graduationYear', 'isPublished'));
    }

    /**
     * Cek hasil kelulusan
     */
    public function checkGraduation(Request $request)
    {
        $request->validate([
            'nisn_nis' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        // Cek apakah pengumuman sudah dipublikasi
        $isPublished = Setting::getValue('is_published', false);
        if (!$isPublished) {
            return back()->with('error', 'Pengumuman kelulusan belum dipublikasikan.');
        }

        // Cari siswa berdasarkan NISN/NIS dan tanggal lahir
        $student = Student::where(function ($query) use ($request) {
            $query->where('nisn', $request->nisn_nis)
                ->orWhere('nis', $request->nisn_nis);
        })
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->first();

        if (!$student) {
            return back()->with('error', 'Data tidak ditemukan. Pastikan NISN/NIS dan tanggal lahir sudah benar.');
        }

        return view('result', compact('student'));
    }

    /**
     * Generate PDF surat kelulusan
     */
    public function generatePDF($id)
    {
        $student = Student::findOrFail($id);

        // Cek apakah siswa lulus
        if (!$student->isLulus()) {
            return back()->with('error', 'Surat kelulusan hanya tersedia untuk siswa yang lulus.');
        }

        // Ambil semua pengaturan sekolah dan pemerintah
        $schoolData = [
            'government_name' => Setting::getValue('government_name', 'PEMERINTAH PROVINSI DKI JAKARTA'),
            'department_name' => Setting::getValue('department_name', 'DINAS PENDIDIKAN'),
            'government_logo' => Setting::getValue('government_logo', ''),
            'school_name' => Setting::getValue('school_name', 'SMA Negeri 1'),
            'school_address' => Setting::getValue('school_address', 'Jl. Pendidikan No. 123, Jakarta'),
            'school_phone' => Setting::getValue('school_phone', '(021) 1234567'),
            'school_email' => Setting::getValue('school_email', 'info@sekolah.com'),
            'school_website' => Setting::getValue('school_website', 'www.sekolah.com'),
            'school_logo' => Setting::getValue('school_logo', ''),
            'principal_name' => Setting::getValue('principal_name', 'Kepala Sekolah'),
            'principal_nip' => Setting::getValue('principal_nip', '196501011990031001'),
            'graduation_year' => Setting::getValue('graduation_year', date('Y')),
        ];

        // Generate QR Code untuk verifikasi
        $verificationUrl = url('/verify/' . $student->id . '/' . md5($student->nisn . $student->tanggal_lahir->format('Y-m-d')));

        // Generate QR Code menggunakan SVG base64 approach (recommended solution)
        try {
            $qrCode = base64_encode(QrCode::format('svg')->size(60)->errorCorrection('H')->generate($verificationUrl));
        } catch (Exception $e) {
            // Fallback jika QR Code generation gagal
            $qrCode = null;
        }

        $pdf = Pdf::loadView('pdf.certificate', compact('student', 'schoolData', 'verificationUrl', 'qrCode'))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Times New Roman',
                'isPhpEnabled' => true
            ]);

        return $pdf->download('surat-kelulusan-' . $student->nisn . '.pdf');
    }

    /**
     * Verifikasi sertifikat melalui QR Code
     */
    public function verifyCertificate($id, $hash)
    {
        $student = Student::findOrFail($id);

        // Verifikasi hash
        $expectedHash = md5($student->nisn . $student->tanggal_lahir->format('Y-m-d'));

        if ($hash !== $expectedHash) {
            abort(404, 'Sertifikat tidak valid');
        }

        $schoolName = Setting::getValue('school_name', 'SMA Negeri 1');

        return view('verify-certificate', compact('student', 'schoolName'));
    }
}
