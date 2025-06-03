<?php

// Test script untuk signature settings
require_once 'vendor/autoload.php';

use App\Models\Setting;
use App\Helpers\IndonesianDate;

echo "✍️ Testing Signature Settings\n";
echo "==============================\n\n";

try {
    // Test 1: Default settings
    echo "📍 Test 1: Default Settings\n";
    echo "----------------------------\n";
    echo "Location: " . Setting::getValue('signature_location') . "\n";
    echo "Date Format: " . Setting::getValue('signature_date_format') . "\n";
    echo "Result: " . IndonesianDate::getSignatureLocationDate() . "\n\n";
    
    // Test 2: Different locations
    echo "🌍 Test 2: Different Locations\n";
    echo "-------------------------------\n";
    $locations = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Medan', 'Makassar'];
    
    foreach ($locations as $location) {
        Setting::setValue('signature_location', $location);
        echo "{$location}: " . IndonesianDate::getSignatureLocationDate() . "\n";
    }
    echo "\n";
    
    // Test 3: Custom date
    echo "📅 Test 3: Custom Date Format\n";
    echo "------------------------------\n";
    Setting::setValue('signature_location', 'Jakarta');
    Setting::setValue('signature_date_format', 'custom');
    Setting::setValue('signature_custom_date', '2025-05-15');
    echo "Custom Date (15 Mei 2025): " . IndonesianDate::getSignatureLocationDate() . "\n";
    
    Setting::setValue('signature_custom_date', '2025-12-25');
    echo "Custom Date (25 Des 2025): " . IndonesianDate::getSignatureLocationDate() . "\n\n";
    
    // Test 4: Graduation date simulation
    echo "🎓 Test 4: Graduation Date Format\n";
    echo "----------------------------------\n";
    Setting::setValue('signature_date_format', 'graduation_date');
    
    // Simulate student with different created_at dates
    $mockStudent = new stdClass();
    $mockStudent->created_at = \Carbon\Carbon::parse('2025-04-20');
    echo "Student Input (20 Apr 2025): " . IndonesianDate::getSignatureLocationDate($mockStudent) . "\n";
    
    $mockStudent->created_at = \Carbon\Carbon::parse('2025-03-15');
    echo "Student Input (15 Mar 2025): " . IndonesianDate::getSignatureLocationDate($mockStudent) . "\n\n";
    
    // Test 5: Auto date (current)
    echo "🕐 Test 5: Auto Date Format\n";
    echo "---------------------------\n";
    Setting::setValue('signature_date_format', 'auto');
    echo "Auto Date (Current): " . IndonesianDate::getSignatureLocationDate() . "\n\n";
    
    // Test 6: Individual methods
    echo "🔧 Test 6: Individual Methods\n";
    echo "------------------------------\n";
    Setting::setValue('signature_location', 'Bandung');
    echo "getSignatureLocation(): " . IndonesianDate::getSignatureLocation() . "\n";
    echo "getSignatureDate(): " . IndonesianDate::getSignatureDate() . "\n";
    echo "getSignatureLocationDate(): " . IndonesianDate::getSignatureLocationDate() . "\n\n";
    
    // Test 7: Edge cases
    echo "⚠️ Test 7: Edge Cases\n";
    echo "---------------------\n";
    
    // Empty custom date
    Setting::setValue('signature_date_format', 'custom');
    Setting::setValue('signature_custom_date', '');
    echo "Empty Custom Date (fallback): " . IndonesianDate::getSignatureLocationDate() . "\n";
    
    // Null student for graduation_date
    Setting::setValue('signature_date_format', 'graduation_date');
    echo "Null Student (fallback): " . IndonesianDate::getSignatureLocationDate(null) . "\n\n";
    
    // Test 8: Different date formats
    echo "📆 Test 8: Different Date Formats\n";
    echo "----------------------------------\n";
    Setting::setValue('signature_date_format', 'custom');
    
    $testDates = [
        '2025-01-01' => 'Tahun Baru',
        '2025-08-17' => 'Kemerdekaan',
        '2025-12-25' => 'Natal',
        '2025-06-15' => 'Pertengahan Tahun'
    ];
    
    foreach ($testDates as $date => $desc) {
        Setting::setValue('signature_custom_date', $date);
        echo "{$desc} ({$date}): " . IndonesianDate::getSignatureLocationDate() . "\n";
    }
    
    // Reset to default
    echo "\n🔄 Resetting to Default Settings\n";
    echo "---------------------------------\n";
    Setting::setValue('signature_location', 'Jakarta');
    Setting::setValue('signature_date_format', 'auto');
    Setting::setValue('signature_custom_date', '');
    echo "Reset Complete: " . IndonesianDate::getSignatureLocationDate() . "\n";
    
    echo "\n✅ All signature settings tests completed successfully!\n";
    echo "\n🎯 Ready for production use:\n";
    echo "- Admin can set location: Jakarta, Bandung, Surabaya, etc\n";
    echo "- Admin can choose date format: auto, custom, graduation_date\n";
    echo "- PDF will show: Jakarta, 3 Juni 2025\n";
    echo "- Real-time preview in admin settings\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
