<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Student;
use Carbon\Carbon;

class SKService
{
    /**
     * Generate nomor surat otomatis
     */
    public static function generateSKNumber($studentId = null)
    {
        $format = Setting::getValue('sk_number_format', 'SK/{counter}/{year}');
        $counterStart = Setting::getValue('sk_counter_start', 1);
        $resetYearly = Setting::getValue('sk_reset_yearly', true);
        $autoGenerate = Setting::getValue('sk_auto_generate', true);
        
        if (!$autoGenerate) {
            return null;
        }
        
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        // Get current counter
        $counterKey = $resetYearly ? "sk_counter_{$currentYear}" : 'sk_counter';
        $currentCounter = Setting::getValue($counterKey, $counterStart);
        
        // Generate nomor surat
        $skNumber = $format;
        $skNumber = str_replace('{counter}', str_pad($currentCounter, 3, '0', STR_PAD_LEFT), $skNumber);
        $skNumber = str_replace('{year}', $currentYear, $skNumber);
        $skNumber = str_replace('{month}', $currentMonth, $skNumber);
        $skNumber = str_replace('{prev_year}', $currentYear - 1, $skNumber);
        
        if ($studentId) {
            $skNumber = str_replace('{student_id}', $studentId, $skNumber);
        }
        
        // Increment counter
        Setting::setValue($counterKey, $currentCounter + 1);
        
        return $skNumber;
    }
    
    /**
     * Get next SK number preview (tanpa increment counter)
     */
    public static function getNextSKNumberPreview($studentId = null)
    {
        $format = Setting::getValue('sk_number_format', 'SK/{counter}/{year}');
        $counterStart = Setting::getValue('sk_counter_start', 1);
        $resetYearly = Setting::getValue('sk_reset_yearly', true);
        
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        // Get current counter (without incrementing)
        $counterKey = $resetYearly ? "sk_counter_{$currentYear}" : 'sk_counter';
        $currentCounter = Setting::getValue($counterKey, $counterStart);
        
        // Generate preview
        $skNumber = $format;
        $skNumber = str_replace('{counter}', str_pad($currentCounter, 3, '0', STR_PAD_LEFT), $skNumber);
        $skNumber = str_replace('{year}', $currentYear, $skNumber);
        $skNumber = str_replace('{month}', $currentMonth, $skNumber);
        $skNumber = str_replace('{prev_year}', $currentYear - 1, $skNumber);
        
        if ($studentId) {
            $skNumber = str_replace('{student_id}', $studentId, $skNumber);
        } else {
            $skNumber = str_replace('{student_id}', '123', $skNumber);
        }
        
        return $skNumber;
    }
    
    /**
     * Reset counter (untuk testing atau reset manual)
     */
    public static function resetCounter($year = null)
    {
        $resetYearly = Setting::getValue('sk_reset_yearly', true);
        $counterStart = Setting::getValue('sk_counter_start', 1);
        
        if ($resetYearly && $year) {
            $counterKey = "sk_counter_{$year}";
        } else {
            $counterKey = 'sk_counter';
        }
        
        Setting::setValue($counterKey, $counterStart);
        
        return true;
    }
    
    /**
     * Get SK template data dengan placeholder yang sudah di-replace
     */
    public static function getSKTemplateData($student = null)
    {
        $schoolData = [
            'government_name' => Setting::getValue('government_name', 'PEMERINTAH PROVINSI DKI JAKARTA'),
            'department_name' => Setting::getValue('department_name', 'DINAS PENDIDIKAN'),
            'school_name' => Setting::getValue('school_name', 'SMA Negeri 1'),
            'graduation_year' => Setting::getValue('graduation_year', date('Y')),
            'principal_name' => Setting::getValue('principal_name', 'Kepala Sekolah'),
            'principal_nip' => Setting::getValue('principal_nip', ''),
            'school_address' => Setting::getValue('school_address', ''),
            'school_phone' => Setting::getValue('school_phone', ''),
            'school_email' => Setting::getValue('school_email', ''),
            'school_website' => Setting::getValue('school_website', ''),
            'government_logo' => Setting::getValue('government_logo', ''),
            'school_logo' => Setting::getValue('school_logo', ''),
        ];
        
        $skData = [
            'sk_title' => Setting::getValue('sk_title', 'SURAT KETERANGAN KELULUSAN'),
            'sk_opening_text' => Setting::getValue('sk_opening_text', 'Yang bertanda tangan di bawah ini, Kepala {school_name}, dengan ini menerangkan bahwa:'),
            'sk_closing_text' => Setting::getValue('sk_closing_text', 'Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.'),
            'sk_graduation_statement' => Setting::getValue('sk_graduation_statement', 'Telah LULUS dari {school_name} pada Tahun Pelajaran {prev_year}/{graduation_year}'),
            'sk_show_photo' => Setting::getValue('sk_show_photo', true),
            'sk_show_qr' => Setting::getValue('sk_show_qr', true),
        ];
        
        // Replace placeholders in SK texts
        $replacements = [
            '{school_name}' => $schoolData['school_name'],
            '{principal_name}' => $schoolData['principal_name'],
            '{graduation_year}' => $schoolData['graduation_year'],
            '{prev_year}' => $schoolData['graduation_year'] - 1,
        ];
        
        foreach ($skData as $key => $value) {
            if (is_string($value)) {
                $skData[$key] = str_replace(array_keys($replacements), array_values($replacements), $value);
            }
        }
        
        return array_merge($schoolData, $skData);
    }
    
    /**
     * Get current counter value
     */
    public static function getCurrentCounter()
    {
        $resetYearly = Setting::getValue('sk_reset_yearly', true);
        $counterStart = Setting::getValue('sk_counter_start', 1);
        $currentYear = date('Y');
        
        $counterKey = $resetYearly ? "sk_counter_{$currentYear}" : 'sk_counter';
        
        return Setting::getValue($counterKey, $counterStart);
    }
    
    /**
     * Set counter value
     */
    public static function setCounter($value, $year = null)
    {
        $resetYearly = Setting::getValue('sk_reset_yearly', true);
        
        if ($resetYearly && $year) {
            $counterKey = "sk_counter_{$year}";
        } else {
            $counterKey = 'sk_counter';
        }
        
        Setting::setValue($counterKey, $value);
        
        return true;
    }
    
    /**
     * Validate SK number format
     */
    public static function validateSKFormat($format)
    {
        $allowedPlaceholders = ['{counter}', '{year}', '{month}', '{student_id}', '{prev_year}'];
        
        // Check if format contains at least {counter}
        if (strpos($format, '{counter}') === false) {
            return false;
        }
        
        // Check for invalid placeholders
        preg_match_all('/\{([^}]+)\}/', $format, $matches);
        foreach ($matches[0] as $placeholder) {
            if (!in_array($placeholder, $allowedPlaceholders)) {
                return false;
            }
        }
        
        return true;
    }
}
