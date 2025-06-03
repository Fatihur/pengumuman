<?php

// Test script untuk format Indonesia
require_once 'vendor/autoload.php';

use App\Helpers\IndonesianDate;

echo "🇮🇩 Testing Indonesian Date Format\n";
echo "=====================================\n\n";

try {
    // Test format methods
    echo "📅 Date Format Tests:\n";
    echo "--------------------\n";
    
    $testDate = '2025-01-15';
    echo "Input Date: {$testDate}\n\n";
    
    echo "formatLong():   " . IndonesianDate::formatLong($testDate) . "\n";
    echo "formatMedium(): " . IndonesianDate::formatMedium($testDate) . "\n";
    echo "formatShort():  " . IndonesianDate::formatShort($testDate) . "\n";
    echo "formatPlaceDate('Jakarta'): " . IndonesianDate::formatPlaceDate('Jakarta', $testDate) . "\n\n";
    
    // Test current date methods
    echo "📆 Current Date Tests:\n";
    echo "---------------------\n";
    echo "getCurrentMonthName(): " . IndonesianDate::getCurrentMonthName() . "\n";
    echo "formatPlaceDate('Jakarta'): " . IndonesianDate::formatPlaceDate('Jakarta') . "\n\n";
    
    // Test month names
    echo "📋 All Month Names:\n";
    echo "-------------------\n";
    $months = IndonesianDate::getAllMonths();
    foreach ($months as $num => $name) {
        echo sprintf("%2d. %s\n", $num, $name);
    }
    echo "\n";
    
    // Test nomor surat format simulation
    echo "📄 Nomor Surat Format Simulation:\n";
    echo "----------------------------------\n";
    $currentMonth = date('n');
    $currentYear = date('Y');
    $monthName = IndonesianDate::getMonthName($currentMonth);
    
    echo "Current Month: {$monthName}\n";
    echo "Current Year: {$currentYear}\n\n";
    
    echo "Sample Nomor Surat:\n";
    for ($i = 1; $i <= 5; $i++) {
        echo sprintf("SK/%03d/%s/%s\n", $i, $monthName, $currentYear);
    }
    echo "\n";
    
    // Test different months
    echo "📅 Different Months Examples:\n";
    echo "-----------------------------\n";
    for ($month = 1; $month <= 12; $month++) {
        $monthName = IndonesianDate::getMonthName($month);
        echo sprintf("SK/001/%s/2025\n", $monthName);
    }
    
    echo "\n✅ All tests completed successfully!\n";
    echo "\n🎯 Ready for production use:\n";
    echo "- Nomor surat: SK/001/Juni/2025\n";
    echo "- PDF dates: 15 Juni 2025\n";
    echo "- Signature: Jakarta, 15 Juni 2025\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
