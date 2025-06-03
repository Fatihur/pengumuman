<?php

namespace App\Helpers;

use Carbon\Carbon;

class IndonesianDate
{
    /**
     * Array bulan dalam bahasa Indonesia
     */
    private static $months = [
        1 => 'Januari',
        2 => 'Februari', 
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    /**
     * Array bulan singkat dalam bahasa Indonesia
     */
    private static $shortMonths = [
        1 => 'Jan',
        2 => 'Feb', 
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Ags',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des'
    ];

    /**
     * Array hari dalam bahasa Indonesia
     */
    private static $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    /**
     * Get nama bulan dalam bahasa Indonesia
     */
    public static function getMonthName(int $month): string
    {
        return self::$months[$month] ?? '';
    }

    /**
     * Get nama bulan singkat dalam bahasa Indonesia
     */
    public static function getShortMonthName(int $month): string
    {
        return self::$shortMonths[$month] ?? '';
    }

    /**
     * Get nama hari dalam bahasa Indonesia
     */
    public static function getDayName(string $englishDay): string
    {
        return self::$days[$englishDay] ?? $englishDay;
    }

    /**
     * Format tanggal lengkap dalam bahasa Indonesia
     * Format: Senin, 15 Januari 2025
     */
    public static function formatLong($date): string
    {
        if (!$date) return '';
        
        $carbon = Carbon::parse($date);
        $dayName = self::getDayName($carbon->format('l'));
        $monthName = self::getMonthName($carbon->month);
        
        return sprintf('%s, %d %s %d', 
            $dayName, 
            $carbon->day, 
            $monthName, 
            $carbon->year
        );
    }

    /**
     * Format tanggal sedang dalam bahasa Indonesia
     * Format: 15 Januari 2025
     */
    public static function formatMedium($date): string
    {
        if (!$date) return '';
        
        $carbon = Carbon::parse($date);
        $monthName = self::getMonthName($carbon->month);
        
        return sprintf('%d %s %d', 
            $carbon->day, 
            $monthName, 
            $carbon->year
        );
    }

    /**
     * Format tanggal pendek dalam bahasa Indonesia
     * Format: 15 Jan 2025
     */
    public static function formatShort($date): string
    {
        if (!$date) return '';
        
        $carbon = Carbon::parse($date);
        $shortMonthName = self::getShortMonthName($carbon->month);
        
        return sprintf('%d %s %d', 
            $carbon->day, 
            $shortMonthName, 
            $carbon->year
        );
    }

    /**
     * Format bulan tahun untuk nomor surat
     * Format: Januari/2025, Februari/2025, dst
     */
    public static function formatMonthYear($date = null): string
    {
        $carbon = $date ? Carbon::parse($date) : Carbon::now();
        $monthName = self::getMonthName($carbon->month);
        
        return sprintf('%s/%d', $monthName, $carbon->year);
    }

    /**
     * Format bulan singkat untuk nomor surat
     * Format: Jan/2025, Feb/2025, dst
     */
    public static function formatShortMonthYear($date = null): string
    {
        $carbon = $date ? Carbon::parse($date) : Carbon::now();
        $shortMonthName = self::getShortMonthName($carbon->month);
        
        return sprintf('%s/%d', $shortMonthName, $carbon->year);
    }

    /**
     * Get current month name in Indonesian
     */
    public static function getCurrentMonthName(): string
    {
        return self::getMonthName(Carbon::now()->month);
    }

    /**
     * Get current short month name in Indonesian
     */
    public static function getCurrentShortMonthName(): string
    {
        return self::getShortMonthName(Carbon::now()->month);
    }

    /**
     * Format untuk tempat dan tanggal surat
     * Format: Jakarta, 15 Januari 2025
     */
    public static function formatPlaceDate(string $place, $date = null): string
    {
        $formattedDate = self::formatMedium($date ?: Carbon::now());
        return sprintf('%s, %s', $place, $formattedDate);
    }

    /**
     * Get all month names
     */
    public static function getAllMonths(): array
    {
        return self::$months;
    }

    /**
     * Get all short month names
     */
    public static function getAllShortMonths(): array
    {
        return self::$shortMonths;
    }

    /**
     * Get all day names
     */
    public static function getAllDays(): array
    {
        return self::$days;
    }

    /**
     * Get signature location and date based on settings
     */
    public static function getSignatureLocationDate($student = null): string
    {
        // Get settings
        $location = \App\Models\Setting::getValue('signature_location', 'Jakarta');
        $dateFormat = \App\Models\Setting::getValue('signature_date_format', 'auto');
        $customDate = \App\Models\Setting::getValue('signature_custom_date', '');

        // Determine date based on format setting
        $date = null;
        switch ($dateFormat) {
            case 'custom':
                if (!empty($customDate)) {
                    $date = $customDate;
                } else {
                    $date = now(); // Fallback to current date
                }
                break;

            case 'graduation_date':
                if ($student && $student->created_at) {
                    $date = $student->created_at;
                } else {
                    $date = now(); // Fallback to current date
                }
                break;

            case 'auto':
            default:
                $date = now();
                break;
        }

        return self::formatPlaceDate($location, $date);
    }

    /**
     * Get signature location only
     */
    public static function getSignatureLocation(): string
    {
        return \App\Models\Setting::getValue('signature_location', 'Jakarta');
    }

    /**
     * Get signature date only based on settings
     */
    public static function getSignatureDate($student = null): string
    {
        $dateFormat = \App\Models\Setting::getValue('signature_date_format', 'auto');
        $customDate = \App\Models\Setting::getValue('signature_custom_date', '');

        // Determine date based on format setting
        $date = null;
        switch ($dateFormat) {
            case 'custom':
                if (!empty($customDate)) {
                    $date = $customDate;
                } else {
                    $date = now(); // Fallback to current date
                }
                break;

            case 'graduation_date':
                if ($student && $student->created_at) {
                    $date = $student->created_at;
                } else {
                    $date = now(); // Fallback to current date
                }
                break;

            case 'auto':
            default:
                $date = now();
                break;
        }

        return self::formatMedium($date);
    }
}
