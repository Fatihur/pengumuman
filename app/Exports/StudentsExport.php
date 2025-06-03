<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithMapping, ShouldAutoSize
{
    protected $students;
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Student::query();

        // Apply filters
        if (!empty($this->filters['status_kelulusan'])) {
            $query->where('status_kelulusan', $this->filters['status_kelulusan']);
        }

        if (!empty($this->filters['kelas'])) {
            $query->where('kelas', 'like', '%' . $this->filters['kelas'] . '%');
        }

        if (!empty($this->filters['program_studi'])) {
            $query->where('program_studi', $this->filters['program_studi']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $this->students = $query->orderBy('nama')->get();
        return $this->students;
    }

    /**
     * @param Student $student
     */
    public function map($student): array
    {
        return [
            $student->nisn,
            $student->nis,
            $student->nama,
            $student->tanggal_lahir ? \Carbon\Carbon::parse($student->tanggal_lahir)->format('d/m/Y') : '',
            $student->kelas,
            $student->program_studi,
            $student->status_kelulusan === 'lulus' ? 'LULUS' : 'TIDAK LULUS',
            $student->no_surat ?? '',
            $student->pesan_khusus ?? '',
            $student->created_at ? $student->created_at->format('d/m/Y H:i') : '',
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NISN',
            'NIS',
            'Nama Lengkap',
            'Tanggal Lahir',
            'Kelas',
            'Program Studi',
            'Status Kelulusan',
            'Nomor Surat',
            'Pesan Khusus',
            'Tanggal Input',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->students->count() + 1;
        
        // Header styling
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'] // Blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Data rows styling
        if ($lastRow > 1) {
            $sheet->getStyle("A2:J{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

            // Alternating row colors
            for ($row = 2; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F8FAFC']
                        ]
                    ]);
                }
            }

            // Status kelulusan conditional formatting
            for ($row = 2; $row <= $lastRow; $row++) {
                $statusCell = "G{$row}";
                $cellValue = $sheet->getCell($statusCell)->getValue();
                
                if ($cellValue === 'LULUS') {
                    $sheet->getStyle($statusCell)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '059669'] // Green
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'D1FAE5'] // Light green
                        ]
                    ]);
                } elseif ($cellValue === 'TIDAK LULUS') {
                    $sheet->getStyle($statusCell)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'DC2626'] // Red
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FEE2E2'] // Light red
                        ]
                    ]);
                }
            }
        }

        // Add summary section
        $summaryRow = $lastRow + 3;
        $sheet->setCellValue("A{$summaryRow}", "RINGKASAN DATA:");
        $sheet->getStyle("A{$summaryRow}:J{$summaryRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '1F2937'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FEF3C7'] // Yellow
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        $sheet->mergeCells("A{$summaryRow}:J{$summaryRow}");

        // Summary data
        $totalStudents = $this->students->count();
        $lulusCount = $this->students->where('status_kelulusan', 'lulus')->count();
        $tidakLulusCount = $this->students->where('status_kelulusan', 'tidak_lulus')->count();
        $belumDitentukan = $this->students->whereNull('status_kelulusan')->count();

        $summaryData = [
            "Total Siswa: {$totalStudents}",
            "Lulus: {$lulusCount}",
            "Tidak Lulus: {$tidakLulusCount}",
            "Belum Ditentukan: {$belumDitentukan}",
            "",
            "Diekspor pada: " . now()->format('d/m/Y H:i:s'),
            "Sistem: Pengumuman Kelulusan"
        ];

        foreach ($summaryData as $index => $data) {
            $row = $summaryRow + 1 + $index;
            $sheet->setCellValue("A{$row}", $data);
            $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                'font' => [
                    'size' => 10,
                    'color' => ['rgb' => '374151']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB']
                    ]
                ]
            ]);
            $sheet->mergeCells("A{$row}:J{$row}");
        }

        return [];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // NISN
            'B' => 12, // NIS
            'C' => 25, // Nama
            'D' => 15, // Tanggal Lahir
            'E' => 15, // Kelas
            'F' => 15, // Program Studi
            'G' => 18, // Status Kelulusan
            'H' => 20, // Nomor Surat
            'I' => 35, // Pesan Khusus
            'J' => 18, // Tanggal Input
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $title = 'Data Siswa';
        
        if (!empty($this->filters['status_kelulusan'])) {
            $title .= ' - ' . ucfirst($this->filters['status_kelulusan']);
        }
        
        if (!empty($this->filters['kelas'])) {
            $title .= ' - ' . $this->filters['kelas'];
        }
        
        return $title;
    }
}
