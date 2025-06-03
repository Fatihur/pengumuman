@echo off
echo ========================================
echo    MENJALANKAN SEEDER SISWA
echo ========================================
echo.

echo 🔄 Menjalankan seeder untuk 100 data siswa...
php artisan db:seed --class=StudentsSeeder

echo.
echo ✅ Seeder selesai dijalankan!
echo.
echo 📊 Untuk melihat data siswa:
echo    - Buka halaman admin: http://localhost/admin/students
echo    - Login dengan: admin@sekolah.com / password
echo.
pause
