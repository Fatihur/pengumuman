@extends('layouts.app')

@section('title', 'Test Protection - Graduation System')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">🔒 Protection Test Page</h1>
            <p class="text-gray-600">Test halaman untuk menguji fitur proteksi client-side</p>
        </div>

        <!-- Protection Status -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-800 mb-4">Status Proteksi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-blue-700">Right-click Protection: Active</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-blue-700">Keyboard Shortcuts: Blocked</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-blue-700">Text Selection: Disabled</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-blue-700">DevTools Detection: Monitoring</span>
                </div>
            </div>
        </div>

        <!-- Test Instructions -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-yellow-800 mb-4">📋 Instruksi Test</h2>
            <div class="space-y-3 text-sm text-yellow-700">
                <p><strong>1. Right-click Test:</strong> Coba klik kanan di area manapun pada halaman ini</p>
                <p><strong>2. Keyboard Test:</strong> Coba tekan F12, Ctrl+Shift+I, Ctrl+U, atau Ctrl+S</p>
                <p><strong>3. Selection Test:</strong> Coba select/highlight text di halaman ini</p>
                <p><strong>4. Image Drag Test:</strong> Coba drag gambar di bawah</p>
                <p><strong>5. DevTools Test:</strong> Coba buka Developer Tools dengan cara apapun</p>
                <p><strong>6. Print Screen Test:</strong> Coba tekan tombol Print Screen</p>
            </div>
        </div>

        <!-- Test Content -->
        <div class="space-y-8">
            <!-- Text Content -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📝 Test Text Selection</h3>
                <p class="text-gray-600 leading-relaxed">
                    Ini adalah contoh teks yang seharusnya tidak bisa diselect atau di-highlight. 
                    Proteksi text selection akan mencegah user untuk memilih teks ini. 
                    Jika proteksi bekerja dengan baik, Anda tidak akan bisa menyeleksi teks ini dengan mouse.
                </p>
                <p class="text-gray-600 leading-relaxed mt-4">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor 
                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis 
                    nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
            </div>

            <!-- Image Content -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🖼️ Test Image Protection</h3>
                <p class="text-gray-600 mb-4">Coba drag gambar di bawah ini. Proteksi akan mencegah drag & drop:</p>
                <div class="flex justify-center">
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj4yMDB4MTUwPC90ZXh0Pgo8L3N2Zz4=" 
                         alt="Test Image" 
                         class="border border-gray-300 rounded-lg shadow-sm">
                </div>
            </div>

            <!-- Interactive Elements -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">⌨️ Test Interactive Elements</h3>
                <p class="text-gray-600 mb-4">Element input masih bisa digunakan normal:</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Input:</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Anda masih bisa mengetik dan select text di sini">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Textarea:</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                  rows="3" 
                                  placeholder="Textarea juga masih berfungsi normal untuk input user"></textarea>
                    </div>
                </div>
            </div>

            <!-- Security Events Log -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-800 mb-4">🚨 Security Events Log</h3>
                <p class="text-red-600 mb-4">Events yang terdeteksi akan muncul di sini:</p>
                <div id="security-events-log" class="bg-white border border-red-200 rounded p-4 min-h-[100px] max-h-[200px] overflow-y-auto">
                    <p class="text-gray-500 text-sm">Belum ada security events terdeteksi...</p>
                </div>
            </div>

            <!-- Test Results -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-4">✅ Expected Results</h3>
                <div class="space-y-2 text-sm text-green-700">
                    <p>• Right-click akan menampilkan peringatan dan tidak menampilkan context menu</p>
                    <p>• Keyboard shortcuts (F12, Ctrl+Shift+I, dll) akan diblokir dengan peringatan</p>
                    <p>• Text tidak bisa diselect kecuali di input/textarea</p>
                    <p>• Gambar tidak bisa di-drag</p>
                    <p>• Developer Tools akan terdeteksi dan halaman akan blur</p>
                    <p>• Print Screen akan terdeteksi dan dicatat</p>
                    <p>• Semua events akan dicatat di server untuk monitoring</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Beranda
            </a>
            @auth
            <a href="{{ route('admin.security.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors ml-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Security Dashboard
            </a>
            @endauth
        </div>
    </div>
</div>

@push('scripts')
<!-- Protection Toggle Script for Testing -->
<script src="{{ asset('js/protection-toggle.js') }}"></script>
<script>
// Enhanced logging for test page
document.addEventListener('DOMContentLoaded', function() {
    const logContainer = document.getElementById('security-events-log');
    let eventCount = 0;
    
    // Override the original logSecurityEvent function to also display on page
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        if (args[0] === '/api/security-log') {
            try {
                const data = JSON.parse(args[1].body);
                displaySecurityEvent(data);
            } catch (e) {
                // Ignore parsing errors
            }
        }
        return originalFetch.apply(this, args);
    };
    
    function displaySecurityEvent(eventData) {
        eventCount++;
        
        // Clear "no events" message
        if (eventCount === 1) {
            logContainer.innerHTML = '';
        }
        
        const eventElement = document.createElement('div');
        eventElement.className = 'border-b border-gray-200 pb-2 mb-2 last:border-b-0';
        eventElement.innerHTML = `
            <div class="flex justify-between items-start">
                <div>
                    <span class="font-medium text-red-600">${eventData.type.replace(/_/g, ' ').toUpperCase()}</span>
                    <p class="text-sm text-gray-600 mt-1">${new Date().toLocaleTimeString()}</p>
                </div>
                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">#${eventCount}</span>
            </div>
        `;
        
        logContainer.appendChild(eventElement);
        logContainer.scrollTop = logContainer.scrollHeight;
    }
    
    // Test DevTools detection
    let devToolsWarningShown = false;
    setInterval(function() {
        const threshold = 160;
        if ((window.outerHeight - window.innerHeight > threshold || 
             window.outerWidth - window.innerWidth > threshold) && 
            !devToolsWarningShown) {
            devToolsWarningShown = true;
            displaySecurityEvent({
                type: 'devtools_detected',
                timestamp: new Date().toISOString()
            });
            
            setTimeout(() => {
                devToolsWarningShown = false;
            }, 5000);
        }
    }, 1000);
});
</script>
@endpush
@endsection
