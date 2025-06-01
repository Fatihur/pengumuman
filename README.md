# Pengumuman Kelulusan Online

Aplikasi web untuk mengumumkan hasil kelulusan siswa secara online dengan sistem yang aman dan mudah digunakan.

## 🚀 Fitur Utama

### Untuk Siswa
- ✅ Cek hasil kelulusan dengan NISN/NIS dan tanggal lahir
- ✅ Unduh surat keterangan kelulusan (PDF) dengan QR Code
- ✅ Tampilan responsif untuk semua perangkat
- ✅ Validasi data real-time
- ✅ Verifikasi sertifikat melalui QR Code

### Untuk Admin
- ✅ Dashboard dengan statistik kelulusan
- ✅ Manajemen data siswa (CRUD)
- ✅ Kontrol publikasi pengumuman
- ✅ Pengaturan sekolah lengkap (nama, alamat, kontak, logo)
- ✅ Export PDF surat kelulusan dengan QR Code
- ✅ Upload logo sekolah
- ✅ Konfigurasi informasi kepala sekolah

## 🛠️ Teknologi

- **Backend**: Laravel 12 (PHP)
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **PDF**: DomPDF
- **QR Code**: SimpleSoftwareIO/simple-qrcode
- **Authentication**: Laravel Auth

## 📋 Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Web server (Apache/Nginx) atau PHP built-in server
- Extension PHP: GD atau Imagick (untuk QR Code)

## 🔧 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd pengumuman
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan pengaturan database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pengumuman_kelulusan
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Setup Database
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 6. Jalankan Server
```bash
php artisan serve
```
Atau gunakan PHP built-in server:
```bash
php -S localhost:8000 -t public
```

## 🚀 Quick Start (Windows)

Untuk pengguna Windows, jalankan file batch yang telah disediakan:

1. **Setup aplikasi**: Jalankan `setup.bat`
2. **Start server**: Jalankan `start-server.bat`

## 👤 Akun Demo

### Admin
- **Email**: admin@sekolah.com
- **Password**: password

### Data Siswa Demo
| NISN | NIS | Nama | Tanggal Lahir | Status |
|------|-----|------|---------------|--------|
| 1234567890 | 12345 | Ahmad Rizki Pratama | 2005-05-15 | LULUS |
| 1234567891 | 12346 | Siti Nurhaliza | 2005-08-20 | LULUS |
| 1234567892 | 12347 | Budi Santoso | 2005-12-10 | TIDAK LULUS |

## 📱 Cara Penggunaan

### Untuk Siswa
1. Buka halaman utama aplikasi
2. Masukkan NISN/NIS dan tanggal lahir
3. Klik "Cek Hasil Kelulusan"
4. Lihat hasil dan unduh surat jika lulus
5. Scan QR Code untuk verifikasi sertifikat

### Untuk Admin
1. Login ke `/login` dengan akun admin
2. Kelola data siswa di menu "Data Siswa"
3. Atur publikasi di menu "Pengaturan"
4. Upload logo sekolah di pengaturan
5. Monitor statistik di Dashboard

## 🎨 Fitur PDF Terbaru

### Surat Kelulusan Professional (1 Halaman)
- ✅ Header dengan logo pemerintah (kiri) dan logo sekolah (kanan)
- ✅ Informasi pemerintah dan dinas di bagian atas
- ✅ Layout kompak dalam 1 halaman A4
- ✅ QR Code di footer untuk verifikasi (tidak ada border)
- ✅ Kotak foto 3x4 untuk siswa (mengganti tanda tangan orang tua)
- ✅ Informasi sekolah lengkap (alamat, telepon, email, website)
- ✅ Layout professional dengan watermark
- ✅ Informasi kepala sekolah dengan NIP
- ✅ Nomor surat otomatis
- ✅ Font dan spacing yang dioptimalkan untuk 1 halaman

### QR Code Verification
- ✅ Setiap sertifikat memiliki QR Code unik (SVG base64)
- ✅ Verifikasi online melalui scan QR Code
- ✅ Halaman verifikasi dengan detail siswa
- ✅ Sistem keamanan hash untuk mencegah pemalsuan
- ✅ Error correction level 'H' untuk scan yang reliable
- ✅ 100% kompatibel dengan DomPDF (proven solution)
- ✅ Tidak memerlukan extension tambahan

## 🔒 Keamanan

- ✅ Validasi input (client & server-side)
- ✅ CSRF protection pada semua form
- ✅ Authentication untuk admin routes
- ✅ Password hashing
- ✅ SQL injection prevention
- ✅ QR Code verification system
- ✅ File upload validation untuk logo

## 📁 Struktur File Baru

```
pengumuman/
├── app/Http/Controllers/
│   ├── AdminController.php (updated)
│   └── StudentController.php (updated)
├── resources/views/
│   ├── admin/settings.blade.php (updated)
│   ├── pdf/certificate.blade.php (redesigned)
│   └── verify-certificate.blade.php (new)
├── storage/app/public/logos/ (logo storage)
└── public/storage/ (symlink)
```

## 🎯 Pengaturan Lengkap

Admin dapat mengkonfigurasi:

### Informasi Pemerintah
- ✅ Nama pemerintah (contoh: PEMERINTAH PROVINSI DKI JAKARTA)
- ✅ Nama dinas (contoh: DINAS PENDIDIKAN)
- ✅ Logo pemerintah (upload) - ditampilkan di kiri header

### Informasi Sekolah
- ✅ Nama sekolah
- ✅ Alamat lengkap
- ✅ Nomor telepon
- ✅ Email sekolah
- ✅ Website sekolah
- ✅ Logo sekolah (upload) - ditampilkan di kanan header

### Informasi Kepala Sekolah
- ✅ Nama kepala sekolah
- ✅ NIP kepala sekolah

### Pengaturan Sistem
- ✅ Tahun kelulusan
- ✅ Status publikasi pengumuman

## 🐛 Troubleshooting

### QR Code Error "imagick extension required" atau "DomPDF SVG issue"
**✅ SOLVED**: Aplikasi menggunakan SVG base64 approach yang proven working
- QR Code: `base64_encode(QrCode::format('svg')->size(60)->errorCorrection('H')->generate($url))`
- Template: `<img src="data:image/svg+xml;base64,{{ $qrCode }}">`
- Tidak memerlukan Imagick extension
- 100% kompatibel dengan DomPDF
- Error correction level 'H' untuk kualitas terbaik

**Referensi solusi**: Berdasarkan community solution yang terbukti working
- Source: GitHub issue discussions
- Tested: Laravel + DomPDF + QrCode library
- Result: Perfect QR Code generation tanpa dependency tambahan

### Logo Tidak Tampil di PDF
- ✅ **Sudah diperbaiki**: Logo menggunakan base64 encoding
- Pastikan `php artisan storage:link` sudah dijalankan
- Upload logo melalui admin panel

### Server Tidak Bisa Start
- Pastikan port tidak digunakan aplikasi lain
- Coba port lain: `php artisan serve --port=8080`
- Atau gunakan: `php -S localhost:8000 -t public`

## 📞 Support

Jika mengalami masalah atau butuh bantuan:
1. Cek dokumentasi Laravel: https://laravel.com/docs
2. Periksa log error di `storage/logs/laravel.log`
3. Pastikan semua persyaratan sistem terpenuhi

## 📄 Lisensi

Aplikasi ini dibuat untuk keperluan edukasi dan dapat digunakan secara bebas.

---

**Dibuat dengan ❤️ menggunakan Laravel & Tailwind CSS**
**Updated: PDF dengan QR Code & Logo Sekolah**
