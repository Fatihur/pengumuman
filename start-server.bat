@echo off
echo Starting Laravel Development Server...
echo.

REM Try different ports
echo Trying port 8000...
php artisan serve --port=8000 2>nul
if %errorlevel% neq 0 (
    echo Port 8000 failed, trying port 8080...
    php artisan serve --port=8080 2>nul
    if %errorlevel% neq 0 (
        echo Port 8080 failed, trying port 3000...
        php artisan serve --port=3000 2>nul
        if %errorlevel% neq 0 (
            echo Port 3000 failed, trying port 5000...
            php artisan serve --port=5000 2>nul
            if %errorlevel% neq 0 (
                echo All ports failed. Please check if PHP is installed and working.
                echo You can also try running: php -S localhost:8000 -t public
                pause
            )
        )
    )
)
