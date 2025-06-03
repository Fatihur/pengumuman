<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentsTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    /**
     * @return array
     */
    public function array(): array
    {
        return [
            [
                '1234567890',
                '12345',
                'Ahmad Budi Santoso',
                '2005-01-15',
                'XII IPA 1',
                'IPA',
                'lulus',
                'Selamat atas kelulusannya'
            ],
            [
                '1234567891',
                '12346',
                'Siti Nurhaliza',
                '2005-03-20',
                'XII IPS 2',
                'IPS',
                'tidak_lulus',
                'Jangan menyerah, terus belajar dan perbaiki diri'
            ],
            [
                '1234567892',
                '12347',
                'Budi Pratama',
                '2005-05-10',
                'XII IPA 2',
                'IPA',
                'lulus',
                'Prestasi yang membanggakan'
            ],
            [
                '1234567893',
                '12348',
                'Dewi Lestari',
                '2005-07-25',
                'XII Bahasa',
                'Bahasa',
                'lulus',
                'Terus berkarya dan berprestasi'
            ],
            [
                '1234567894',
                '12349',
                'Eko Wijaya',
                '2005-09-12',
                'XII IPS 1',
                'IPS',
                'tidak_lulus',
                'Evaluasi dan perbaiki kekurangan'
            ]
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
            'Pesan Khusus'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Set header style
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5']
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

        // Set data rows style
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:H{$lastRow}")->applyFromArray([
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

        // Set alternating row colors
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC']
                    ]
                ]);
            }
        }

        // Add instruction row
        $instructionRow = $lastRow + 2;
        $sheet->setCellValue("A{$instructionRow}", "PETUNJUK PENGISIAN:");
        $sheet->getStyle("A{$instructionRow}:H{$instructionRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '1F2937'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FEF3C7']
            ]
        ]);
        $sheet->mergeCells("A{$instructionRow}:H{$instructionRow}");

        // Add instructions
        $instructions = [
            "1. NISN: Nomor Induk Siswa Nasional (10 digit angka, wajib diisi)",
            "2. NIS: Nomor Induk Siswa (angka, wajib diisi)",
            "3. Nama Lengkap: Nama lengkap siswa (wajib diisi)",
            "4. Tanggal Lahir: Format YYYY-MM-DD (contoh: 2005-01-15)",
            "5. Kelas: Contoh XII IPA 1, XII IPS 2, XII Bahasa",
            "6. Program Studi: IPA, IPS, atau Bahasa",
            "7. Status Kelulusan: lulus atau tidak_lulus",
            "8. Pesan Khusus: Pesan untuk siswa (opsional)",
            "",
            "CATATAN PENTING:",
            "• Nomor surat akan di-generate otomatis oleh sistem",
            "• Hapus baris contoh data sebelum mengisi data siswa",
            "• Pastikan format data sesuai dengan petunjuk",
            "• NISN harus unik dan tidak boleh duplikat"
        ];

        foreach ($instructions as $index => $instruction) {
            $row = $instructionRow + 1 + $index;
            $sheet->setCellValue("A{$row}", $instruction);
            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                'font' => [
                    'size' => 10,
                    'color' => ['rgb' => '374151']
                ]
            ]);
            $sheet->mergeCells("A{$row}:H{$row}");
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
            'H' => 35, // Pesan Khusus
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Template Import Siswa';
    }
}
