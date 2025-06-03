# ✍️ Pengaturan Lokasi dan Tanggal Tanda Tangan

## 🎯 Fitur yang Diimplementasikan

Fitur untuk mengatur lokasi dan tanggal tanda tangan pada surat kelulusan melalui panel admin, dengan 3 opsi format tanggal yang fleksibel.

## 🛠️ Implementasi

### **1. Database Migration**

**File:** `database/migrations/2025_06_03_130624_add_signature_location_settings_to_settings_table.php`

**Settings Baru:**
```php
'signature_location' => 'Jakarta'                    // Default lokasi
'signature_date_format' => 'auto'                    // Format tanggal: auto/custom/graduation_date
'signature_custom_date' => ''                        // Tanggal khusus (jika custom)
```

### **2. IndonesianDate Helper Enhancement**

**File:** `app/Helpers/IndonesianDate.php`

**Methods Baru:**
```php
getSignatureLocationDate($student = null)    // Lokasi + tanggal berdasarkan setting
getSignatureLocation()                       // Lokasi saja
getSignatureDate($student = null)           // Tanggal saja berdasarkan setting
```

**Logic Format Tanggal:**
- **auto**: Tanggal sekarang (default)
- **custom**: Tanggal khusus yang diset admin
- **graduation_date**: Tanggal input siswa (created_at)

### **3. PDF Certificate Update**

**File:** `resources/views/pdf/certificate.blade.php`

**Sebelum:**
```php
{{ \App\Helpers\IndonesianDate::formatPlaceDate('Jakarta') }}
```

**Sesudah:**
```php
{{ \App\Helpers\IndonesianDate::getSignatureLocationDate($student) }}
```

### **4. Admin Settings Interface**

**File:** `resources/views/admin/settings.blade.php`

**Section Baru:** "Pengaturan Tanda Tangan"

**Fields:**
1. **Lokasi Tanda Tangan** (required)
2. **Format Tanggal Tanda Tangan** (dropdown)
3. **Tanggal Khusus** (conditional field)
4. **Preview Tanda Tangan** (real-time preview)

### **5. AdminController Enhancement**

**File:** `app/Http/Controllers/AdminController.php`

**Validation Baru:**
```php
'signature_location' => 'required|string|max:100',
'signature_date_format' => 'required|in:auto,custom,graduation_date',
'signature_custom_date' => 'nullable|date',
```

**Save Settings:**
```php
Setting::setValue('signature_location', $request->signature_location);
Setting::setValue('signature_date_format', $request->signature_date_format);
Setting::setValue('signature_custom_date', $request->signature_custom_date);
```

## 📊 Format Tanggal Options

### **1. Auto (Tanggal Sekarang)**
```
Setting: signature_date_format = 'auto'
Result: Jakarta, 3 Juni 2025
Usage: Tanggal saat PDF di-generate
```

### **2. Custom (Tanggal Khusus)**
```
Setting: signature_date_format = 'custom'
Custom Date: 2025-05-15
Result: Jakarta, 15 Mei 2025
Usage: Tanggal tetap untuk semua surat
```

### **3. Graduation Date (Tanggal Input Siswa)**
```
Setting: signature_date_format = 'graduation_date'
Student Created: 2025-04-20
Result: Jakarta, 20 April 2025
Usage: Tanggal saat siswa diinput ke sistem
```

## 🎨 Admin Interface

### **Pengaturan Tanda Tangan Section:**

#### **Lokasi Tanda Tangan:**
```html
<input type="text" name="signature_location" 
       value="Jakarta" placeholder="Contoh: Jakarta, Bandung, Surabaya" required>
```

#### **Format Tanggal:**
```html
<select name="signature_date_format" onchange="toggleCustomDateField()">
    <option value="auto">Tanggal Sekarang (Otomatis)</option>
    <option value="custom">Tanggal Khusus</option>
    <option value="graduation_date">Tanggal Input Siswa</option>
</select>
```

#### **Tanggal Khusus (Conditional):**
```html
<input type="date" name="signature_custom_date" 
       style="display: none" id="custom_date_field">
```

#### **Preview Real-time:**
```html
<div class="bg-blue-50 border border-blue-200 rounded-md p-4">
    <h4>Preview Tanda Tangan:</h4>
    <div id="signature_preview">
        <span id="preview_location">Jakarta</span>, 
        <span id="preview_date">3 Juni 2025</span>
        <br>Kepala Sekolah
    </div>
</div>
```

## 🔧 JavaScript Functionality

### **Toggle Custom Date Field:**
```javascript
function toggleCustomDateField() {
    const dateFormat = document.getElementById('signature_date_format').value;
    const customDateField = document.getElementById('custom_date_field');
    
    if (dateFormat === 'custom') {
        customDateField.style.display = 'block';
    } else {
        customDateField.style.display = 'none';
    }
    
    updateSignaturePreview();
}
```

### **Real-time Preview Update:**
```javascript
function updateSignaturePreview() {
    const location = document.getElementById('signature_location').value || 'Jakarta';
    const dateFormat = document.getElementById('signature_date_format').value;
    const customDate = document.getElementById('signature_custom_date').value;
    
    // Update preview dengan format Indonesia
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    let dateText = '';
    if (dateFormat === 'custom' && customDate) {
        const date = new Date(customDate);
        dateText = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    } else {
        const today = new Date();
        dateText = `${today.getDate()} ${months[today.getMonth()]} ${today.getFullYear()}`;
    }
    
    document.getElementById('preview_location').textContent = location;
    document.getElementById('preview_date').textContent = dateText;
}
```

## 🧪 Testing Scenarios

### **Test 1: Auto Date (Default)**
```
Settings:
- signature_location: "Jakarta"
- signature_date_format: "auto"

Expected PDF Output:
Jakarta, 3 Juni 2025
Kepala Sekolah
```

### **Test 2: Custom Date**
```
Settings:
- signature_location: "Bandung"
- signature_date_format: "custom"
- signature_custom_date: "2025-05-15"

Expected PDF Output:
Bandung, 15 Mei 2025
Kepala Sekolah
```

### **Test 3: Graduation Date**
```
Settings:
- signature_location: "Surabaya"
- signature_date_format: "graduation_date"

Student created_at: 2025-04-20

Expected PDF Output:
Surabaya, 20 April 2025
Kepala Sekolah
```

### **Test 4: Different Locations**
```
Test locations:
- Jakarta → Jakarta, 3 Juni 2025
- Bandung → Bandung, 3 Juni 2025
- Surabaya → Surabaya, 3 Juni 2025
- Yogyakarta → Yogyakarta, 3 Juni 2025
```

### **Test 5: Admin Interface**
```
1. Go to /admin/settings
2. Scroll to "Pengaturan Tanda Tangan"
3. Change location → Preview updates
4. Change date format → Custom field shows/hides
5. Set custom date → Preview updates
6. Save settings → Success message
```

## 📁 File yang Dibuat/Dimodifikasi

### **File Baru:**
```
database/migrations/2025_06_03_130624_add_signature_location_settings_to_settings_table.php
SIGNATURE_SETTINGS_FEATURE.md - Documentation
```

### **File yang Dimodifikasi:**
```
app/Helpers/IndonesianDate.php - Added signature methods
resources/views/pdf/certificate.blade.php - Use dynamic signature
resources/views/admin/settings.blade.php - Added signature settings UI
app/Http/Controllers/AdminController.php - Added validation & save
```

## 🎯 Use Cases

### **Sekolah Multi-Lokasi:**
```
Cabang Jakarta: signature_location = "Jakarta"
Cabang Bandung: signature_location = "Bandung"
Cabang Surabaya: signature_location = "Surabaya"
```

### **Tanggal Wisuda Khusus:**
```
Wisuda: 15 Mei 2025
Setting: signature_date_format = "custom"
Custom Date: 2025-05-15
Result: Semua surat bertanggal 15 Mei 2025
```

### **Tanggal Sesuai Input:**
```
Siswa A diinput: 20 April 2025
Siswa B diinput: 25 April 2025
Setting: signature_date_format = "graduation_date"
Result: Masing-masing sesuai tanggal input
```

## ✅ Status

**🎯 SIGNATURE SETTINGS: FULLY IMPLEMENTED**

**📋 Features Complete:**
- ✅ **Database migration** dengan 3 settings baru
- ✅ **IndonesianDate helper** dengan signature methods
- ✅ **PDF certificate** menggunakan dynamic signature
- ✅ **Admin interface** dengan form lengkap
- ✅ **Real-time preview** dengan JavaScript
- ✅ **3 format tanggal** - auto, custom, graduation_date
- ✅ **Validation & save** di AdminController
- ✅ **Conditional fields** - custom date field

**🚀 Signature Options:**
- ✅ **Lokasi fleksibel** - Jakarta, Bandung, Surabaya, dll
- ✅ **Auto date** - Tanggal saat generate PDF
- ✅ **Custom date** - Tanggal tetap untuk semua surat
- ✅ **Graduation date** - Tanggal input siswa
- ✅ **Format Indonesia** - 3 Juni 2025, 15 Mei 2025, dll

**🎮 Ready to Use:**
1. **Login admin**: `/admin/settings`
2. **Scroll to**: "Pengaturan Tanda Tangan"
3. **Set location**: Jakarta, Bandung, dll
4. **Choose date format**: Auto/Custom/Graduation
5. **Preview**: Real-time preview
6. **Save**: Settings tersimpan
7. **Generate PDF**: Signature sesuai setting

**🎉 SIGNATURE SETTINGS BERHASIL DIIMPLEMENTASIKAN!**

Admin sekarang dapat mengatur lokasi dan tanggal tanda tangan dengan fleksibel melalui interface yang user-friendly dengan preview real-time.
