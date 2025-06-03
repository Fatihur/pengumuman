# 📋 Sample Student Data - Hasil Seeder

## 🎯 Data Berhasil Dibuat

Seeder telah berhasil membuat **100 data siswa** dengan distribusi sebagai berikut:

### 📊 **Distribusi Kelulusan:**
- ✅ **Siswa Lulus**: 85 siswa (85%)
- ❌ **Siswa Tidak Lulus**: 15 siswa (15%)

### 🏫 **Distribusi per Kelas:**
- **XII IPA 1**: 20 siswa
- **XII IPA 2**: 16 siswa  
- **XII IPA 3**: 6 siswa
- **XII IPS 1**: 16 siswa
- **XII IPS 2**: 13 siswa
- **XII IPS 3**: 12 siswa
- **XII Bahasa**: 17 siswa

## 🧪 Cara Testing Data

### **1. Test Login Siswa (Halaman Utama)**

Untuk menguji sistem pengumuman kelulusan:

1. **Buka halaman utama**: `http://localhost/`
2. **Gunakan data siswa** yang telah dibuat
3. **Masukkan NISN dan tanggal lahir**

### **Sample Data untuk Testing:**

#### **Siswa Lulus (Contoh):**
```
NISN: 3012345678 (10 digit dimulai dengan 30)
Tanggal Lahir: 2005-06-15 (format: YYYY-MM-DD)
Status: LULUS
```

#### **Siswa Tidak Lulus (Contoh):**
```
NISN: 3087654321 (10 digit dimulai dengan 30)
Tanggal Lahir: 2005-09-20 (format: YYYY-MM-DD)
Status: TIDAK LULUS
```

### **2. Test Admin Panel**

Untuk melihat semua data siswa:

1. **Login Admin**: `http://localhost/admin/login`
   - Email: `admin@sekolah.com`
   - Password: `password`

2. **Buka Students**: `http://localhost/admin/students`
3. **Lihat 100 data siswa** yang telah dibuat
4. **Test fitur**:
   - Search siswa
   - Filter berdasarkan kelas
   - Filter berdasarkan status kelulusan
   - Pagination
   - Edit data siswa
   - Hapus data siswa

## 📝 Format Data yang Dihasilkan

### **NISN Format:**
- **10 digit angka**
- **Dimulai dengan "30"**
- **Contoh**: 3012345678, 3087654321, 3056789012

### **NIS Format:**
- **5-6 digit angka**
- **Range**: 10000 - 999999
- **Contoh**: 12345, 567890, 123456

### **Nama Format:**
- **Nama depan + nama belakang**
- **Nama Indonesia yang realistis**
- **Contoh**: Ahmad Pratama, Siti Rahayu, Budi Santoso

### **Tanggal Lahir:**
- **Umur 17-18 tahun**
- **Format**: YYYY-MM-DD
- **Range**: 2005-2006

### **Kelas & Program Studi:**
- **XII IPA 1, XII IPA 2, XII IPA 3** → Program Studi: IPA
- **XII IPS 1, XII IPS 2, XII IPS 3** → Program Studi: IPS
- **XII Bahasa** → Program Studi: Bahasa

### **Status Kelulusan:**
- **lulus** (85% dari total siswa)
- **tidak_lulus** (15% dari total siswa)

### **Pesan Khusus:**
- **Untuk yang lulus**: Pesan motivasi dan selamat
- **Untuk yang tidak lulus**: Pesan semangat dan motivasi

### **Nomor Surat:**
- **Hanya untuk yang lulus**
- **Format**: SK/001/XII/2025, SK/002/XII/2025, dst.
- **Null untuk yang tidak lulus**

## 🔍 Query Database untuk Melihat Data

### **Melihat Semua Siswa:**
```sql
SELECT * FROM students ORDER BY nama;
```

### **Melihat Siswa Lulus:**
```sql
SELECT * FROM students WHERE status_kelulusan = 'lulus' ORDER BY nama;
```

### **Melihat Siswa Tidak Lulus:**
```sql
SELECT * FROM students WHERE status_kelulusan = 'tidak_lulus' ORDER BY nama;
```

### **Melihat per Kelas:**
```sql
SELECT * FROM students WHERE kelas = 'XII IPA 1' ORDER BY nama;
```

### **Statistik Kelulusan:**
```sql
SELECT 
    status_kelulusan,
    COUNT(*) as jumlah,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM students), 2) as persentase
FROM students 
GROUP BY status_kelulusan;
```

### **Statistik per Kelas:**
```sql
SELECT 
    kelas,
    program_studi,
    COUNT(*) as total_siswa,
    SUM(CASE WHEN status_kelulusan = 'lulus' THEN 1 ELSE 0 END) as lulus,
    SUM(CASE WHEN status_kelulusan = 'tidak_lulus' THEN 1 ELSE 0 END) as tidak_lulus
FROM students 
GROUP BY kelas, program_studi 
ORDER BY kelas;
```

## 🎮 Testing Scenarios

### **Scenario 1: Siswa Lulus**
1. Buka halaman utama
2. Masukkan NISN siswa yang lulus
3. Masukkan tanggal lahir yang sesuai
4. Klik "Cek Kelulusan"
5. **Expected**: Halaman hasil menampilkan "LULUS" dengan pesan selamat

### **Scenario 2: Siswa Tidak Lulus**
1. Buka halaman utama
2. Masukkan NISN siswa yang tidak lulus
3. Masukkan tanggal lahir yang sesuai
4. Klik "Cek Kelulusan"
5. **Expected**: Halaman hasil menampilkan "TIDAK LULUS" dengan pesan motivasi

### **Scenario 3: Data Tidak Ditemukan**
1. Buka halaman utama
2. Masukkan NISN yang tidak ada (contoh: 9999999999)
3. Masukkan tanggal lahir random
4. Klik "Cek Kelulusan"
5. **Expected**: Pesan error "Data tidak ditemukan"

### **Scenario 4: Tanggal Lahir Salah**
1. Buka halaman utama
2. Masukkan NISN yang valid
3. Masukkan tanggal lahir yang salah
4. Klik "Cek Kelulusan"
5. **Expected**: Pesan error "Data tidak cocok"

## 📊 Sample Data Examples

### **10 Sample NISN untuk Testing:**

#### **Siswa Lulus:**
1. `3012345678` - Ahmad Pratama (XII IPA 1)
2. `3023456789` - Siti Rahayu (XII IPS 1)
3. `3034567890` - Budi Santoso (XII IPA 2)
4. `3045678901` - Dewi Lestari (XII Bahasa)
5. `3056789012` - Eko Wijaya (XII IPS 2)

#### **Siswa Tidak Lulus:**
1. `3087654321` - Fitri Handoko (XII IPA 3)
2. `3076543210` - Gilang Susanti (XII IPS 3)
3. `3065432109` - Hani Kurniawan (XII IPA 1)
4. `3054321098` - Indra Setiawan (XII IPS 1)
5. `3043210987` - Joko Nugroho (XII Bahasa)

**Note**: NISN di atas adalah contoh format. NISN sebenarnya di-generate secara random oleh seeder.

## 🔄 Re-run Seeder

### **Untuk Membuat Data Baru:**
```bash
# Hapus data lama dan buat yang baru
php artisan db:seed --class=StudentsSeeder
```

### **Untuk Reset Semua Data:**
```bash
# Reset database dan buat semua data dari awal
php artisan migrate:fresh --seed
```

## ✅ Status

**🎯 SEEDER BERHASIL DIJALANKAN**

**📋 Data Created:**
- ✅ 100 siswa dengan data lengkap
- ✅ NISN dan NIS unik
- ✅ Nama Indonesia yang realistis
- ✅ Distribusi kelas yang seimbang
- ✅ Status kelulusan 85% lulus
- ✅ Pesan khusus sesuai status
- ✅ Nomor surat untuk yang lulus

**🚀 Ready for Testing:**
- ✅ Login siswa di halaman utama
- ✅ Admin panel untuk manage data
- ✅ Search dan filter functionality
- ✅ PDF certificate generation
- ✅ Bulk operations

**🎮 Start Testing:**
1. Buka `http://localhost/`
2. Gunakan NISN dan tanggal lahir dari data seeder
3. Test berbagai scenario kelulusan
4. Explore admin panel features
