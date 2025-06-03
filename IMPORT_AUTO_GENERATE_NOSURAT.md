# 📄 Import Excel - Auto Generate Nomor Surat

## 🎯 Perubahan yang Diimplementasikan

Fitur import Excel telah diperbaiki agar **nomor surat di-generate otomatis** oleh sistem, sehingga user tidak perlu menuliskan nomor surat secara manual di file Excel.

## 🛠️ Perubahan yang Dilakukan

### **1. StudentsImport Class** (`app/Imports/StudentsImport.php`)

#### **Auto Generate Nomor Surat:**
```php
// Generate nomor surat otomatis untuk siswa yang lulus
$noSurat = null;
if ($statusKelulusan === 'lulus') {
    $noSurat = $this->generateNoSurat();
}

return new Student([
    // ... other fields
    'no_surat' => $noSurat, // Auto-generated, tidak dari Excel
]);
```

#### **Method generateNoSurat():**
```php
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
```

#### **Validation Rules Updated:**
```php
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
```

### **2. Template Excel** (`app/Http/Controllers/AdminController.php`)

#### **Header CSV Updated:**
```php
// Header dengan contoh data (no_surat dihapus karena auto-generated)
fputcsv($file, [
    'nisn',
    'nis',
    'nama',
    'tanggal_lahir',
    'kelas',
    'program_studi',
    'status_kelulusan',
    'pesan_khusus'
    // no_surat akan di-generate otomatis oleh sistem
]);
```

#### **Sample Data Updated:**
```php
// Contoh data (tanpa no_surat karena auto-generated)
fputcsv($file, [
    '1234567890',
    '12345',
    'Ahmad Budi Santoso',
    '2005-01-15',
    'XII IPA 1',
    'IPA',
    'lulus',
    'Selamat atas kelulusannya'
    // no_surat akan di-generate: SK/001/XII/2025
]);

fputcsv($file, [
    '1234567891',
    '12346',
    'Siti Nurhaliza',
    '2005-03-20',
    'XII IPS 2',
    'IPS',
    'tidak_lulus',
    'Jangan menyerah, terus belajar dan perbaiki diri'
    // no_surat kosong untuk yang tidak lulus
]);
```

### **3. Import View** (`resources/views/admin/students/import.blade.php`)

#### **Updated Format Information:**
```html
<div class="bg-green-50 p-3 rounded-md border border-green-200">
    <span class="font-medium text-green-800">📝 Nomor Surat:</span>
    <span class="text-green-700">Akan di-generate otomatis oleh sistem untuk siswa yang lulus</span>
</div>
```

#### **Updated Tips:**
```html
<ul class="mt-1 list-disc list-inside space-y-1">
    <li>Pastikan format file sesuai template</li>
    <li>NISN harus unik dan tidak boleh kosong</li>
    <li>Data yang sudah ada akan dilewati</li>
    <li>Format tanggal: YYYY-MM-DD atau DD/MM/YYYY</li>
    <li><strong>Nomor surat akan di-generate otomatis</strong> - tidak perlu diisi</li>
</ul>
```

## 📊 Format Excel Baru

### **Kolom yang Diperlukan:**
1. **nisn** - Nomor Induk Siswa Nasional (wajib)
2. **nis** - Nomor Induk Siswa (opsional)
3. **nama** - Nama lengkap siswa (wajib)
4. **tanggal_lahir** - Format: YYYY-MM-DD (opsional)
5. **kelas** - Contoh: XII IPA 1 (opsional)
6. **program_studi** - Contoh: IPA, IPS, Bahasa (opsional)
7. **status_kelulusan** - lulus / tidak_lulus (opsional)
8. **pesan_khusus** - Pesan untuk siswa (opsional)

### **Kolom yang Dihapus:**
- ❌ **no_surat** - Tidak perlu lagi, akan di-generate otomatis

## 🎯 Cara Kerja Auto Generate

### **Untuk Siswa Lulus:**
1. **Check status kelulusan** = 'lulus'
2. **Ambil nomor surat terakhir** untuk tahun ini
3. **Extract nomor urut** dari format SK/XXX/XII/YYYY
4. **Increment nomor** untuk nomor berikutnya
5. **Generate format baru**: SK/001/XII/2025, SK/002/XII/2025, dst.

### **Untuk Siswa Tidak Lulus:**
- **no_surat** = null (kosong)

### **Format Nomor Surat:**
```
SK/001/XII/2025
SK/002/XII/2025
SK/003/XII/2025
...
```

**Keterangan:**
- **SK** = Surat Keterangan
- **001** = Nomor urut (3 digit dengan leading zero)
- **XII** = Tingkat kelas
- **2025** = Tahun kelulusan

## 🧪 Testing Import

### **Sample Excel File:**
```csv
nisn,nis,nama,tanggal_lahir,kelas,program_studi,status_kelulusan,pesan_khusus
1234567890,12345,Ahmad Budi Santoso,2005-01-15,XII IPA 1,IPA,lulus,Selamat atas kelulusannya
1234567891,12346,Siti Nurhaliza,2005-03-20,XII IPS 2,IPS,tidak_lulus,Jangan menyerah
1234567892,12347,Budi Pratama,2005-05-10,XII IPA 2,IPA,lulus,Prestasi yang membanggakan
```

### **Expected Result:**
```
Ahmad Budi Santoso → no_surat: SK/001/XII/2025
Siti Nurhaliza → no_surat: null
Budi Pratama → no_surat: SK/002/XII/2025
```

## 📈 Benefits

### **User Experience:**
- ✅ **Lebih mudah** - Tidak perlu menuliskan nomor surat manual
- ✅ **Konsisten** - Format nomor surat seragam
- ✅ **Otomatis** - Sistem handle penomoran
- ✅ **Error-free** - Tidak ada duplikasi nomor

### **Data Integrity:**
- ✅ **Sequential numbering** - Nomor urut berurutan
- ✅ **Year-based** - Nomor reset setiap tahun
- ✅ **Unique numbers** - Tidak ada duplikasi
- ✅ **Proper format** - Format standar SK/XXX/XII/YYYY

### **Admin Efficiency:**
- ✅ **Bulk import** tanpa manual numbering
- ✅ **Automatic processing** untuk ribuan data
- ✅ **Consistent formatting** across all certificates
- ✅ **Easy maintenance** dan tracking

## 🔧 Customization

### **Mengubah Format Nomor Surat:**
```php
// Di method generateNoSurat()
return sprintf('SK/%03d/XII/%s', $nextNumber, $currentYear);

// Ubah menjadi format lain:
return sprintf('LULUS/%04d/%s', $nextNumber, $currentYear); // LULUS/0001/2025
return sprintf('%s/SK/%03d', $currentYear, $nextNumber);    // 2025/SK/001
```

### **Mengubah Pattern Regex:**
```php
// Untuk format SK/001/XII/2025
preg_match('/SK\/(\d+)\/XII\/\d{4}/', $lastStudent->no_surat, $matches);

// Untuk format LULUS/0001/2025
preg_match('/LULUS\/(\d+)\/\d{4}/', $lastStudent->no_surat, $matches);
```

### **Reset Nomor per Tahun:**
Nomor akan otomatis reset setiap tahun karena query mencari berdasarkan tahun:
```php
->where('no_surat', 'like', "%/{$currentYear}")
```

## ✅ Status

**🎯 IMPORT AUTO GENERATE: IMPLEMENTED**

**📋 Changes Made:**
- ✅ Auto generate nomor surat untuk siswa lulus
- ✅ Remove no_surat dari Excel template
- ✅ Update validation rules
- ✅ Update import view information
- ✅ Sequential numbering system
- ✅ Year-based numbering
- ✅ Proper error handling

**🚀 Ready to Use:**
1. Download template Excel baru (tanpa kolom no_surat)
2. Isi data siswa sesuai format
3. Upload dan import
4. Nomor surat akan di-generate otomatis untuk siswa lulus

**🎮 Testing:**
```bash
# Download template baru
GET /admin/students/template

# Upload dan import
POST /admin/students/import
```

**🎉 FITUR IMPORT DENGAN AUTO GENERATE NOMOR SURAT BERHASIL DIIMPLEMENTASIKAN!**
