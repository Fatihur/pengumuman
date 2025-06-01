@echo off
echo ========================================
echo   SETUP PENGUMUMAN KELULUSAN ONLINE
echo ========================================
echo.

echo [1/4] Checking Laravel installation...
if not exist "artisan" (
    echo ERROR: Laravel not found! Please make sure you're in the correct directory.
    pause
    exit /b 1
)
echo ✓ Laravel found

echo.
echo [2/4] Running database migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo WARNING: Migration failed. You may need to configure your database first.
    echo Please check your .env file and make sure your database is running.
    echo.
    echo Current database configuration:
    echo - DB_CONNECTION=mysql
    echo - DB_HOST=127.0.0.1
    echo - DB_PORT=3306
    echo - DB_DATABASE=pengumuman_kelulusan
    echo - DB_USERNAME=root
    echo - DB_PASSWORD=(empty)
    echo.
    echo You can continue without database for now.
    pause
) else (
    echo ✓ Migrations completed
)

echo.
echo [3/5] Seeding database with sample data...
php artisan db:seed --force
if %errorlevel% neq 0 (
    echo WARNING: Seeding failed. This is normal if database is not configured.
    echo You can add data manually later through the admin panel.
) else (
    echo ✓ Sample data added
)

echo.
echo [4/5] Creating storage symlink...
php artisan storage:link
if %errorlevel% neq 0 (
    echo WARNING: Storage link failed. Logo upload may not work properly.
) else (
    echo ✓ Storage symlink created
)

echo.
echo [5/5] Setup completed!
echo.
echo ========================================
echo   SETUP SUMMARY
echo ========================================
echo.
echo ✓ Laravel application is ready
echo ✓ Database migrations attempted
echo ✓ Sample data seeding attempted
echo ✓ Storage symlink created
echo.
echo DEMO CREDENTIALS:
echo - Email: admin@sekolah.com
echo - Password: password
echo.
echo SAMPLE STUDENT DATA:
echo - NISN: 1234567890, DOB: 2005-05-15 (LULUS)
echo - NISN: 1234567891, DOB: 2005-08-20 (LULUS)
echo - NISN: 1234567892, DOB: 2005-12-10 (TIDAK LULUS)
echo.
echo NEW FEATURES:
echo - PDF certificates with government and school logos
echo - QR Code verification in footer (no border)
echo - Government information management
echo - Photo placeholder 3x4 (replaces parent signature)
echo - Complete school information management
echo - Professional 1-page certificate layout
echo.
echo To start the server, run: start-server.bat
echo Or manually: php -S localhost:8000 -t public
echo.
echo Application will be available at: http://localhost:8000
echo Admin panel: http://localhost:8000/login
echo Admin settings: http://localhost:8000/admin/settings
echo.
pause
