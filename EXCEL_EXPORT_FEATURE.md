# 📊 Excel Export Feature - Data Siswa dengan Format Professional

## 🎯 Fitur yang Diimplementasikan

Fitur export data siswa dalam bentuk Excel (.xlsx) dengan format professional, styling lengkap, dan ringkasan data.

## 🛠️ Implementasi

### **1. StudentsExport Class**

**File:** `app/Exports/StudentsExport.php`

**Features:**
- ✅ **Professional styling** dengan colors dan borders
- ✅ **Conditional formatting** untuk status kelulusan
- ✅ **Alternating row colors** untuk readability
- ✅ **Auto-sized columns** sesuai content
- ✅ **Summary section** dengan statistik data
- ✅ **Filtering support** berdasarkan status, kelas, program studi
- ✅ **Search functionality** dalam export
- ✅ **Dynamic filename** dengan timestamp dan filter

**Concerns Implemented:**
```php
class StudentsExport implements 
    FromCollection,      // Data source
    WithHeadings,        // Column headers
    WithStyles,          // Cell styling
    WithColumnWidths,    // Column widths
    WithTitle,           // Sheet title
    WithMapping,         // Data mapping
    ShouldAutoSize       // Auto-size columns
```

### **2. AdminController Method**

**File:** `app/Http/Controllers/AdminController.php`

**Method:** `exportStudentsExcel(Request $request)`

**Features:**
- ✅ **Filter support** - status_kelulusan, kelas, program_studi, search
- ✅ **Dynamic filename** - timestamp + filter info
- ✅ **Query string preservation** - maintain current filters

### **3. Route Configuration**

**File:** `routes/web.php`

**Route:** `GET /admin/students/export-excel`
**Name:** `admin.students.export.excel`

### **4. UI Integration**

**File:** `resources/views/admin/students/index.blade.php`

**Button:** Export Excel (blue button next to Export CSV)

## 📊 Excel Format Structure

### **Column Structure:**
| Column | Width | Description | Format |
|--------|-------|-------------|---------|
| A (NISN) | 15 | Nomor Induk Siswa Nasional | Text |
| B (NIS) | 12 | Nomor Induk Siswa | Text |
| C (Nama) | 25 | Nama Lengkap Siswa | Text |
| D (Tanggal Lahir) | 15 | Tanggal Lahir | dd/mm/yyyy |
| E (Kelas) | 15 | Kelas Siswa | Text |
| F (Program Studi) | 15 | Program Studi | Text |
| G (Status) | 18 | Status Kelulusan | LULUS/TIDAK LULUS |
| H (No Surat) | 20 | Nomor Surat Kelulusan | Text |
| I (Pesan) | 35 | Pesan Khusus | Text |
| J (Tanggal Input) | 18 | Tanggal Input Data | dd/mm/yyyy hh:mm |

### **Professional Styling:**

#### **Header Row:**
```php
'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']], // Blue
'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
```

#### **Data Rows:**
- **Alternating colors**: White dan Light Gray (#F8FAFC)
- **Thin borders**: Gray borders untuk semua cells
- **Vertical center alignment**

#### **Conditional Formatting:**
```php
// Status LULUS
'font' => ['bold' => true, 'color' => ['rgb' => '059669']], // Green
'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']] // Light green

// Status TIDAK LULUS
'font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']], // Red
'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']] // Light red
```

### **Summary Section:**

**Location:** Di bawah data dengan spacing

**Content:**
```
RINGKASAN DATA:
Total Siswa: 150
Lulus: 120
Tidak Lulus: 25
Belum Ditentukan: 5

Diekspor pada: 15/12/2024 14:30:25
Sistem: Pengumuman Kelulusan
```

**Styling:**
- **Header**: Yellow background (#FEF3C7), bold text
- **Data**: Gray borders, merged cells
- **Font**: 10pt untuk data, 12pt untuk header

## 🔧 Filtering & Search

### **Supported Filters:**
1. **Status Kelulusan**: lulus, tidak_lulus
2. **Kelas**: Partial match (XII IPA, XII IPS, etc.)
3. **Program Studi**: IPA, IPS, Bahasa
4. **Search**: Nama, NISN, NIS

### **Filter Implementation:**
```php
// Controller
$filters = [
    'status_kelulusan' => $request->get('status_kelulusan'),
    'kelas' => $request->get('kelas'),
    'program_studi' => $request->get('program_studi'),
    'search' => $request->get('search'),
];

// Export class
$query = Student::query();

if (!empty($this->filters['status_kelulusan'])) {
    $query->where('status_kelulusan', $this->filters['status_kelulusan']);
}

if (!empty($this->filters['search'])) {
    $query->where(function($q) use ($search) {
        $q->where('nama', 'like', "%{$search}%")
          ->orWhere('nisn', 'like', "%{$search}%")
          ->orWhere('nis', 'like', "%{$search}%");
    });
}
```

### **Dynamic Filename:**
```php
// Base filename with timestamp
$filename = 'data_siswa_' . date('Y-m-d_H-i-s');

// Add filter info
if (!empty($filters['status_kelulusan'])) {
    $filename .= '_' . $filters['status_kelulusan'];
}

if (!empty($filters['kelas'])) {
    $filename .= '_' . str_replace(' ', '_', $filters['kelas']);
}

$filename .= '.xlsx';

// Examples:
// data_siswa_2024-12-15_14-30-25.xlsx
// data_siswa_2024-12-15_14-30-25_lulus.xlsx
// data_siswa_2024-12-15_14-30-25_lulus_XII_IPA_1.xlsx
```

## 🎨 UI/UX Features

### **Export Button:**
```html
<a href="{{ route('admin.students.export.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">
    <svg class="w-4 h-4 inline mr-2">...</svg>
    Export Excel
</a>
```

**Features:**
- ✅ **Blue color** - Distinguishes from CSV export (green)
- ✅ **Excel icon** - Visual indicator
- ✅ **Query string preservation** - Maintains current filters
- ✅ **Hover effects** - Interactive feedback

### **Button Placement:**
- **Location**: Next to Export CSV button
- **Order**: Reset → Export CSV → Export Excel → Import Excel
- **Consistent styling** dengan button lainnya

## 📈 Data Mapping

### **Date Formatting:**
```php
// Tanggal Lahir
$student->tanggal_lahir ? \Carbon\Carbon::parse($student->tanggal_lahir)->format('d/m/Y') : ''

// Tanggal Input
$student->created_at ? $student->created_at->format('d/m/Y H:i') : ''
```

### **Status Formatting:**
```php
// Status Kelulusan
$student->status_kelulusan === 'lulus' ? 'LULUS' : 'TIDAK LULUS'
```

### **Data Handling:**
```php
// Handle null values
$student->no_surat ?? ''
$student->pesan_khusus ?? ''
$student->nis ?? ''
```

## 🧪 Testing

### **Test Export All Data:**
1. **Go to**: `/admin/students`
2. **Click**: "Export Excel" button
3. **Expected**: Download file `data_siswa_YYYY-MM-DD_HH-MM-SS.xlsx`

### **Test Export with Filters:**
1. **Apply filters**: Status = lulus, Kelas = XII IPA 1
2. **Click**: "Export Excel" button
3. **Expected**: Download file `data_siswa_YYYY-MM-DD_HH-MM-SS_lulus_XII_IPA_1.xlsx`
4. **Content**: Only filtered data in Excel

### **Test Excel Content:**
1. **Open downloaded file** di Excel/LibreOffice
2. **Check styling**: Blue header, alternating rows, conditional formatting
3. **Check data**: All columns populated correctly
4. **Check summary**: Statistics at bottom
5. **Check formatting**: Dates, status, borders

### **Test Different Filters:**
```
No Filter: data_siswa_2024-12-15_14-30-25.xlsx
Status Lulus: data_siswa_2024-12-15_14-30-25_lulus.xlsx
Kelas XII IPA 1: data_siswa_2024-12-15_14-30-25_XII_IPA_1.xlsx
Search "Ahmad": data_siswa_2024-12-15_14-30-25.xlsx (with search results)
```

## 📁 File Structure

### **New Files:**
```
app/Exports/StudentsExport.php - Excel export class dengan styling
```

### **Modified Files:**
```
app/Http/Controllers/AdminController.php - Added exportStudentsExcel method
routes/web.php - Added export-excel route
resources/views/admin/students/index.blade.php - Added Export Excel button
```

### **Documentation:**
```
EXCEL_EXPORT_FEATURE.md - This documentation
```

## ✅ Status

**🎯 EXCEL EXPORT: IMPLEMENTED**

**📋 Features Complete:**
- ✅ **Professional Excel export** dengan styling lengkap
- ✅ **Conditional formatting** untuk status kelulusan
- ✅ **Summary section** dengan statistik data
- ✅ **Filter support** - status, kelas, program studi, search
- ✅ **Dynamic filename** dengan timestamp dan filter info
- ✅ **UI integration** dengan button di students index
- ✅ **Query string preservation** untuk maintain filters

**🚀 Export Features:**
- ✅ **10 columns** dengan data lengkap siswa
- ✅ **Professional styling** - colors, borders, fonts
- ✅ **Auto-sized columns** untuk readability
- ✅ **Alternating row colors** untuk visual clarity
- ✅ **Conditional formatting** - green untuk lulus, red untuk tidak lulus
- ✅ **Summary statistics** - total, lulus, tidak lulus, belum ditentukan
- ✅ **Export timestamp** dan system info

**🎮 Ready to Use:**
1. Login admin: `/admin/students`
2. Apply filters (optional): status, kelas, program studi, search
3. Click "Export Excel" button
4. Download professional Excel file dengan styling dan summary

**🎉 EXCEL EXPORT FEATURE BERHASIL DIIMPLEMENTASIKAN!**

Fitur export Excel dengan format professional, conditional formatting, summary section, dan filter support sudah siap digunakan.
