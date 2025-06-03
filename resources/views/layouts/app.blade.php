<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pengumuman Kelulusan')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <!-- Protection CSS -->
    <link rel="stylesheet" href="{{ asset('css/protection.css') }}">

    <!-- Logo Fix CSS -->
    <link rel="stylesheet" href="{{ asset('css/logo-fix.css') }}">
    
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Global Protection Styles */
        * {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
            -webkit-touch-callout: none !important;
            -webkit-tap-highlight-color: transparent !important;
        }
        
        /* Allow selection for input elements */
        input, textarea, [contenteditable="true"], [contenteditable=""] {
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
            user-select: text !important;
        }
        
        /* Disable image dragging */
        img {
            -webkit-user-drag: none !important;
            -khtml-user-drag: none !important;
            -moz-user-drag: none !important;
            -o-user-drag: none !important;
            user-drag: none !important;
            pointer-events: none !important;
        }
        
        /* Re-enable pointer events for interactive images */
        button img, a img, .interactive img {
            pointer-events: auto !important;
        }
        
        /* Disable text highlighting */
        ::selection {
            background: transparent !important;
        }
        
        ::-moz-selection {
            background: transparent !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">
                        <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">
                            🎓 Pengumuman Kelulusan
                        </a>
                    </h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                            Login Admin
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mb-6">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mb-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Sistem Pengumuman Kelulusan. All rights reserved.</p>
            <p class="text-gray-400 text-sm mt-2">Made with ❤️ by <a href="https://github.com/fatihur" class="underline">Fatih</a></p>
        </div>
    </footer>

    <!-- Loading overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <div class="loading mx-auto mb-4"></div>
            <p class="text-gray-600">Sedang memproses...</p>
        </div>
    </div>

    <script>
        // Show loading overlay
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        }, 5000);
    </script>

    <!-- Client-side Protection Script -->
    <script src="{{ asset('js/protection-clean.js') }}"></script>

    <!-- Logo Background Remover Script -->
    <script src="{{ asset('js/logo-background-remover.js') }}"></script>

    @stack('scripts')
</body>
</html>
