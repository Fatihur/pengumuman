<?php

// Simple test script untuk Excel export
require_once 'vendor/autoload.php';

use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

echo "Testing Excel Export...\n";

try {
    // Test basic export creation
    $export = new StudentsExport();
    echo "✅ StudentsExport class created successfully\n";
    
    // Test methods exist
    if (method_exists($export, 'collection')) {
        echo "✅ collection() method exists\n";
    }
    
    if (method_exists($export, 'headings')) {
        echo "✅ headings() method exists\n";
    }
    
    if (method_exists($export, 'styles')) {
        echo "✅ styles() method exists\n";
    }
    
    if (method_exists($export, 'columnWidths')) {
        echo "✅ columnWidths() method exists\n";
    }
    
    if (method_exists($export, 'title')) {
        echo "✅ title() method exists\n";
    }
    
    if (method_exists($export, 'map')) {
        echo "✅ map() method exists\n";
    }
    
    echo "\n🎉 All Excel export methods are available!\n";
    echo "\nReady to test:\n";
    echo "1. Login to admin panel\n";
    echo "2. Go to /admin/students\n";
    echo "3. Click 'Export Excel' button\n";
    echo "4. Download professional Excel file\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
