/**
 * Enhanced Client-side Protection Script
 * Multiple layers of protection against inspection
 */

// Immediate protection - execute right away
(function() {
    'use strict';

    console.log('🔒 Initializing protection...');

    // Aggressive right-click protection
    const blockRightClick = function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        showWarning('🚫 Klik kanan dinonaktifkan!');
        return false;
    };

    // Add multiple event listeners for right-click
    document.addEventListener('contextmenu', blockRightClick, true);
    document.addEventListener('contextmenu', blockRightClick, false);
    window.addEventListener('contextmenu', blockRightClick, true);

    // Also block on document and body
    if (document.body) {
        document.body.addEventListener('contextmenu', blockRightClick, true);
    }

    // Block when body is ready
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('contextmenu', blockRightClick, true);
        document.documentElement.addEventListener('contextmenu', blockRightClick, true);
    });

    // Show warning function
    function showWarning(message) {
        // Remove existing warnings
        const existing = document.querySelectorAll('.protection-warning');
        existing.forEach(el => el.remove());

        // Create warning element
        const warning = document.createElement('div');
        warning.className = 'protection-warning';
        warning.textContent = message;
        warning.style.cssText = `
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            background: #ef4444 !important;
            color: white !important;
            padding: 15px 20px !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            z-index: 2147483647 !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
            font-family: Arial, sans-serif !important;
            max-width: 300px !important;
            word-wrap: break-word !important;
            animation: slideInWarning 0.3s ease-out !important;
        `;

        // Add to page
        document.body.appendChild(warning);

        // Auto remove
        setTimeout(() => {
            if (warning.parentNode) {
                warning.style.animation = 'slideOutWarning 0.3s ease-in';
                setTimeout(() => {
                    if (warning.parentNode) {
                        warning.remove();
                    }
                }, 300);
            }
        }, 3000);
    }

    // Add warning animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInWarning {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutWarning {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    // Keyboard protection
    const blockKeyboard = function(e) {
        // F12
        if (e.keyCode === 123) {
            e.preventDefault();
            e.stopPropagation();
            showWarning('🚫 F12 dinonaktifkan!');
            return false;
        }

        // Ctrl+Shift+I (DevTools)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
            e.preventDefault();
            e.stopPropagation();
            showWarning('🚫 Developer Tools dinonaktifkan!');
            return false;
        }

        // Ctrl+Shift+J (Console)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 74) {
            e.preventDefault();
            e.stopPropagation();
            showWarning('🚫 Console dinonaktifkan!');
            return false;
        }

        // Ctrl+U (View Source)
        if (e.ctrlKey && e.keyCode === 85) {
            e.preventDefault();
            e.stopPropagation();
            showWarning('🚫 View Source dinonaktifkan!');
            return false;
        }

        // Ctrl+S (Save)
        if (e.ctrlKey && e.keyCode === 83) {
            e.preventDefault();
            e.stopPropagation();
            showWarning('🚫 Save dinonaktifkan!');
            return false;
        }
    };

    // Add keyboard protection
    document.addEventListener('keydown', blockKeyboard, true);
    window.addEventListener('keydown', blockKeyboard, true);

    // Text selection protection
    const blockSelection = function(e) {
        const allowedElements = ['INPUT', 'TEXTAREA'];
        if (!allowedElements.includes(e.target.tagName) && e.target.contentEditable !== 'true') {
            e.preventDefault();
            return false;
        }
    };

    document.addEventListener('selectstart', blockSelection, true);

    // Drag protection
    const blockDrag = function(e) {
        e.preventDefault();
        showWarning('🚫 Drag dinonaktifkan!');
        return false;
    };

    document.addEventListener('dragstart', blockDrag, true);

    // Print protection
    window.addEventListener('beforeprint', function(e) {
        e.preventDefault();
        showWarning('🚫 Print dinonaktifkan!');
        return false;
    });

    // DevTools detection
    let devToolsOpen = false;
    setInterval(function() {
        const threshold = 160;
        if ((window.outerHeight - window.innerHeight > threshold ||
             window.outerWidth - window.innerWidth > threshold) && !devToolsOpen) {
            devToolsOpen = true;
            document.body.style.filter = 'blur(5px)';
            showWarning('🚫 Developer Tools terdeteksi!');
        } else if (window.outerHeight - window.innerHeight <= threshold &&
                   window.outerWidth - window.innerWidth <= threshold && devToolsOpen) {
            devToolsOpen = false;
            document.body.style.filter = 'none';
        }
    }, 500);

    // Add protection indicator
    setTimeout(function() {
        const indicator = document.createElement('div');
        indicator.textContent = '🔒 Protected';
        indicator.style.cssText = `
            position: fixed;
            bottom: 10px;
            left: 10px;
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 11px;
            z-index: 999998;
            opacity: 0.8;
            font-family: monospace;
        `;
        document.body.appendChild(indicator);
    }, 1000);

    console.log('🔒 Protection activated successfully');

})();
            type: type,
            timestamp: new Date().toISOString(),
            userAgent: navigator.userAgent,
            url: window.location.href,
            details: details
        };

        // Send to server for logging
        fetch('/api/security-log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(logData)
        }).catch(() => {
            // Silently fail if logging endpoint doesn't exist
        });

        // Also log to console for debugging (in development)
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            console.warn('Security attempt logged:', logData);
        }
    }

    // Show warning message
    function showWarning(message) {
        if (!config.showWarningMessages) return;
        
        // Create or update warning element
        let warningEl = document.getElementById('security-warning');
        if (!warningEl) {
            warningEl = document.createElement('div');
            warningEl.id = 'security-warning';
            warningEl.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #ef4444;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                font-family: Arial, sans-serif;
                font-size: 14px;
                font-weight: 500;
                z-index: 999999;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                max-width: 300px;
                word-wrap: break-word;
                animation: slideIn 0.3s ease-out;
            `;
            document.body.appendChild(warningEl);

            // Add animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }
        
        warningEl.textContent = message;
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            if (warningEl && warningEl.parentNode) {
                warningEl.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => {
                    if (warningEl && warningEl.parentNode) {
                        warningEl.parentNode.removeChild(warningEl);
                    }
                }, 300);
            }
        }, 3000);
    }

    // Disable right-click context menu
    function disableRightClick() {
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            e.stopPropagation();
            logSecurityAttempt('right_click', { 
                element: e.target.tagName,
                className: e.target.className 
            });
            showWarning(messages.rightClick);
            return false;
        }, true);
    }

    // Disable keyboard shortcuts
    function disableKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            const key = e.key.toLowerCase();
            const ctrl = e.ctrlKey;
            const shift = e.shiftKey;
            const alt = e.altKey;
            const meta = e.metaKey;

            // List of disabled shortcuts
            const disabledShortcuts = [
                // Developer tools
                { key: 'f12' },
                { ctrl: true, shift: true, key: 'i' }, // Chrome DevTools
                { ctrl: true, shift: true, key: 'j' }, // Chrome Console
                { ctrl: true, shift: true, key: 'c' }, // Chrome Inspector
                { ctrl: true, key: 'u' }, // View Source
                { meta: true, alt: true, key: 'i' }, // Safari DevTools
                { meta: true, alt: true, key: 'c' }, // Safari Console
                
                // Print and save
                { ctrl: true, key: 'p' }, // Print
                { ctrl: true, key: 's' }, // Save
                { meta: true, key: 'p' }, // Print (Mac)
                { meta: true, key: 's' }, // Save (Mac)
                
                // Selection and copy
                { ctrl: true, key: 'a' }, // Select All
                { ctrl: true, key: 'c' }, // Copy
                { ctrl: true, key: 'v' }, // Paste
                { ctrl: true, key: 'x' }, // Cut
                { meta: true, key: 'a' }, // Select All (Mac)
                { meta: true, key: 'c' }, // Copy (Mac)
                { meta: true, key: 'v' }, // Paste (Mac)
                { meta: true, key: 'x' }, // Cut (Mac)
                
                // Other shortcuts
                { key: 'printscreen' },
                { alt: true, key: 'printscreen' },
                { ctrl: true, shift: true, key: 'delete' }, // Task Manager
            ];

            // Check if current combination is disabled
            const isDisabled = disabledShortcuts.some(shortcut => {
                return (!shortcut.ctrl || ctrl) &&
                       (!shortcut.shift || shift) &&
                       (!shortcut.alt || alt) &&
                       (!shortcut.meta || meta) &&
                       (!shortcut.key || key === shortcut.key);
            });

            if (isDisabled) {
                e.preventDefault();
                e.stopPropagation();
                logSecurityAttempt('keyboard_shortcut', { 
                    key: key,
                    ctrl: ctrl,
                    shift: shift,
                    alt: alt,
                    meta: meta
                });
                showWarning(messages.keyboardShortcut);
                return false;
            }
        }, true);
    }

    // Disable text selection
    function disableTextSelection() {
        // CSS approach
        const style = document.createElement('style');
        style.textContent = `
            * {
                -webkit-user-select: none !important;
                -moz-user-select: none !important;
                -ms-user-select: none !important;
                user-select: none !important;
                -webkit-touch-callout: none !important;
                -webkit-tap-highlight-color: transparent !important;
            }
            
            input, textarea, [contenteditable] {
                -webkit-user-select: text !important;
                -moz-user-select: text !important;
                -ms-user-select: text !important;
                user-select: text !important;
            }
        `;
        document.head.appendChild(style);

        // JavaScript approach
        document.addEventListener('selectstart', function(e) {
            if (!e.target.matches('input, textarea, [contenteditable]')) {
                e.preventDefault();
                logSecurityAttempt('text_selection', { 
                    element: e.target.tagName 
                });
                showWarning(messages.textSelection);
                return false;
            }
        }, true);

        document.addEventListener('mousedown', function(e) {
            if (!e.target.matches('input, textarea, [contenteditable], button, a')) {
                e.preventDefault();
            }
        }, true);
    }

    // Disable image dragging
    function disableImageDragging() {
        document.addEventListener('dragstart', function(e) {
            if (e.target.tagName === 'IMG') {
                e.preventDefault();
                logSecurityAttempt('image_drag', { 
                    src: e.target.src 
                });
                return false;
            }
        }, true);
    }

    // Disable print screen (limited effectiveness)
    function disablePrintScreen() {
        document.addEventListener('keyup', function(e) {
            if (e.key === 'PrintScreen') {
                logSecurityAttempt('print_screen');
                showWarning(messages.printScreen);
                
                // Clear clipboard (doesn't work in all browsers)
                try {
                    navigator.clipboard.writeText('');
                } catch (err) {
                    // Silently fail
                }
            }
        }, true);
    }

    // Developer tools detection
    function detectDevTools() {
        let devtools = {
            open: false,
            orientation: null
        };

        const threshold = 160;

        setInterval(function() {
            if (window.outerHeight - window.innerHeight > threshold || 
                window.outerWidth - window.innerWidth > threshold) {
                
                if (!devtools.open) {
                    devtools.open = true;
                    logSecurityAttempt('devtools_detected', {
                        outerHeight: window.outerHeight,
                        innerHeight: window.innerHeight,
                        outerWidth: window.outerWidth,
                        innerWidth: window.innerWidth
                    });
                    showWarning(messages.devTools);
                    
                    if (config.redirectOnDetection) {
                        setTimeout(() => {
                            window.location.href = config.redirectUrl;
                        }, 2000);
                    }
                }
            } else {
                devtools.open = false;
            }
        }, 500);

        // Console detection
        let consoleDetected = false;
        Object.defineProperty(window, 'console', {
            get: function() {
                if (!consoleDetected) {
                    consoleDetected = true;
                    logSecurityAttempt('console_access');
                    showWarning(messages.devTools);
                }
                return console;
            }
        });
    }

    // Disable common inspection methods
    function disableInspection() {
        // Override console methods
        const noop = function() {};
        window.console = window.console || {};
        const methods = ['log', 'debug', 'info', 'warn', 'error', 'assert', 'dir', 'dirxml', 'group', 'groupEnd', 'time', 'timeEnd', 'count', 'trace', 'profile', 'profileEnd'];
        
        for (let i = 0; i < methods.length; i++) {
            window.console[methods[i]] = noop;
        }

        // Disable eval
        window.eval = function() {
            logSecurityAttempt('eval_attempt');
            throw new Error('eval is disabled for security reasons');
        };

        // Override toString to hide source
        Function.prototype.toString = function() {
            return 'function() { [native code] }';
        };
    }

    // Blur page when focus is lost (indicates possible DevTools)
    function blurOnFocusLoss() {
        let blurred = false;
        
        window.addEventListener('blur', function() {
            if (!blurred) {
                blurred = true;
                document.body.style.filter = 'blur(5px)';
                logSecurityAttempt('window_blur');
            }
        });

        window.addEventListener('focus', function() {
            if (blurred) {
                blurred = false;
                document.body.style.filter = 'none';
            }
        });
    }

    // Initialize protection
    function initProtection() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initProtection);
            return;
        }

        try {
            if (config.disableRightClick) disableRightClick();
            if (config.disableKeyboardShortcuts) disableKeyboardShortcuts();
            if (config.disableTextSelection) disableTextSelection();
            if (config.disableImageDragging) disableImageDragging();
            if (config.disablePrintScreen) disablePrintScreen();
            if (config.enableDevToolsDetection) detectDevTools();
            
            disableInspection();
            blurOnFocusLoss();

            // Log successful initialization
            logSecurityAttempt('protection_initialized', {
                features: Object.keys(config).filter(key => config[key])
            });

        } catch (error) {
            // Silently handle errors
            console.error('Protection initialization failed:', error);
        }
    }

    // Start protection
    initProtection();

    // Prevent script removal
    Object.freeze(window);
    Object.freeze(document);

})();
