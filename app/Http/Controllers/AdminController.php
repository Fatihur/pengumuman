<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Setting;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        $totalStudents = Student::count();
        $lulusCount = Student::lulus()->count();
        $tidakLulusCount = Student::tidakLulus()->count();
        $isPublished = Setting::getValue('is_published', false);

        return view('admin.dashboard', compact('totalStudents', 'lulusCount', 'tidakLulusCount', 'isPublished'));
    }

    /**
     * Daftar siswa
     */
    public function students()
    {
        $students = Student::orderBy('nama')->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Form tambah siswa
     */
    public function createStudent()
    {
        return view('admin.students.create');
    }

    /**
     * Simpan siswa baru
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:students',
            'nis' => 'required|unique:students',
            'nama' => 'required',
            'tanggal_lahir' => 'required|date',
            'kelas' => 'required',
            'program_studi' => 'required',
            'status_kelulusan' => 'required|in:lulus,tidak_lulus',
        ]);

        Student::create($request->all());

        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Form edit siswa
     */
    public function editStudent(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update siswa
     */
    public function updateStudent(Request $request, Student $student)
    {
        $request->validate([
            'nisn' => 'required|unique:students,nisn,' . $student->id,
            'nis' => 'required|unique:students,nis,' . $student->id,
            'nama' => 'required',
            'tanggal_lahir' => 'required|date',
            'kelas' => 'required',
            'program_studi' => 'required',
            'status_kelulusan' => 'required|in:lulus,tidak_lulus',
        ]);

        $student->update($request->all());

        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Hapus siswa
     */
    public function destroyStudent(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Pengaturan
     */
    public function settings()
    {
        $settings = [
            'government_name' => Setting::getValue('government_name', 'PEMERINTAH PROVINSI DKI JAKARTA'),
            'department_name' => Setting::getValue('department_name', 'DINAS PENDIDIKAN'),
            'government_logo' => Setting::getValue('government_logo', ''),
            'school_name' => Setting::getValue('school_name', 'SMA Negeri 1'),
            'graduation_year' => Setting::getValue('graduation_year', date('Y')),
            'principal_name' => Setting::getValue('principal_name', 'Kepala Sekolah'),
            'principal_nip' => Setting::getValue('principal_nip', '196501011990031001'),
            'school_address' => Setting::getValue('school_address', 'Jl. Pendidikan No. 123, Jakarta'),
            'school_phone' => Setting::getValue('school_phone', '(021) 1234567'),
            'school_email' => Setting::getValue('school_email', 'info@sekolah.com'),
            'school_website' => Setting::getValue('school_website', 'www.sekolah.com'),
            'school_logo' => Setting::getValue('school_logo', ''),
            'is_published' => Setting::getValue('is_published', false),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update pengaturan
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'government_name' => 'required',
            'department_name' => 'required',
            'government_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_name' => 'required',
            'graduation_year' => 'required|numeric',
            'principal_name' => 'required',
            'principal_nip' => 'nullable',
            'school_address' => 'required',
            'school_phone' => 'nullable',
            'school_email' => 'nullable|email',
            'school_website' => 'nullable',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Setting::setValue('government_name', $request->government_name);
        Setting::setValue('department_name', $request->department_name);
        Setting::setValue('school_name', $request->school_name);
        Setting::setValue('graduation_year', $request->graduation_year);
        Setting::setValue('principal_name', $request->principal_name);
        Setting::setValue('principal_nip', $request->principal_nip);
        Setting::setValue('school_address', $request->school_address);
        Setting::setValue('school_phone', $request->school_phone);
        Setting::setValue('school_email', $request->school_email);
        Setting::setValue('school_website', $request->school_website);

        // Handle government logo upload
        if ($request->hasFile('government_logo')) {
            $logoPath = $request->file('government_logo')->store('logos', 'public');
            Setting::setValue('government_logo', $logoPath);
        }

        // Handle school logo upload
        if ($request->hasFile('school_logo')) {
            $logoPath = $request->file('school_logo')->store('logos', 'public');
            Setting::setValue('school_logo', $logoPath);
        }

        Setting::setValue('is_published', $request->has('is_published'));

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
