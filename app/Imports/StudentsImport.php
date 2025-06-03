<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;
use Throwable;
use Exception;

class StudentsImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnError, 
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Skip jika NISN kosong
            if (empty($row['nisn'])) {
                $this->skippedCount++;
                return null;
            }

            // Cek apakah siswa sudah ada berdasarkan NISN
            $existingStudent = Student::where('nisn', $row['nisn'])->first();
            if ($existingStudent) {
                $this->skippedCount++;
                $this->errors[] = "NISN {$row['nisn']} sudah ada dalam database";
                return null;
            }

            // Parse tanggal lahir
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                try {
                    // Coba berbagai format tanggal
                    if (is_numeric($row['tanggal_lahir'])) {
                        // Excel date serial number
                        $tanggalLahir = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($row['tanggal_lahir'] - 2);
                    } else {
                        // String format
                        $tanggalLahir = Carbon::parse($row['tanggal_lahir']);
                    }
                } catch (Exception $e) {
                    $tanggalLahir = Carbon::now()->subYears(17); // Default umur 17 tahun
                }
            } else {
                $tanggalLahir = Carbon::now()->subYears(17);
            }

            // Normalize status kelulusan
            $statusKelulusan = 'tidak_lulus'; // default
            if (!empty($row['status_kelulusan'])) {
                $status = strtolower(trim($row['status_kelulusan']));
                if (in_array($status, ['lulus', 'l', 'ya', 'yes', '1', 'true'])) {
                    $statusKelulusan = 'lulus';
                }
            }

            // Generate nomor surat otomatis untuk siswa yang lulus
            $noSurat = null;
            if ($statusKelulusan === 'lulus') {
                $noSurat = $this->generateNoSurat();
            }

            $this->importedCount++;

            return new Student([
                'nisn' => $row['nisn'],
                'nis' => $row['nis'] ?? '',
                'nama' => $row['nama'] ?? '',
                'tanggal_lahir' => $tanggalLahir,
                'kelas' => $row['kelas'] ?? '',
                'program_studi' => $row['program_studi'] ?? '',
                'status_kelulusan' => $statusKelulusan,
                'pesan_khusus' => $row['pesan_khusus'] ?? null,
                'no_surat' => $noSurat, // Auto-generated, tidak dari Excel
            ]);

        } catch (Throwable $e) {
            $this->skippedCount++;
            $this->errors[] = "Error pada baris NISN {$row['nisn']}: " . $e->getMessage();
            return null;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'nisn' => 'required|string|max:20',
            'nis' => 'nullable|string|max:20',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable',
            'kelas' => 'nullable|string|max:50',
            'program_studi' => 'nullable|string|max:100',
            'status_kelulusan' => 'nullable|string',
            'pesan_khusus' => 'nullable|string',
            // no_surat dihapus karena akan di-generate otomatis
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'nisn.required' => 'NISN wajib diisi',
            'nisn.max' => 'NISN maksimal 20 karakter',
            'nama.required' => 'Nama wajib diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Get import statistics
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Handle error during import
     */
    public function onError(Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    /**
     * Handle validation failure
     */
    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
        }
    }

    /**
     * Generate nomor surat otomatis untuk siswa yang lulus
     */
    private function generateNoSurat(): string
    {
        // Ambil nomor surat terakhir untuk tahun ini
        $currentYear = date('Y');
        $lastStudent = Student::whereNotNull('no_surat')
            ->where('no_surat', 'like', "%/{$currentYear}")
            ->orderBy('no_surat', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastStudent && $lastStudent->no_surat) {
            // Extract nomor dari format SK/001/XII/2025
            preg_match('/SK\/(\d+)\/XII\/\d{4}/', $lastStudent->no_surat, $matches);
            if (isset($matches[1])) {
                $nextNumber = intval($matches[1]) + 1;
            }
        }

        // Format: SK/001/XII/2025
        return sprintf('SK/%03d/XII/%s', $nextNumber, $currentYear);
    }
}
