/**
 * Enhanced Client-side Protection Script
 * Protects against right-click, keyboard shortcuts, and developer tools
 */

// Immediate protection - execute right away
(function() {
    'use strict';
    
    console.log('🔒 Initializing enhanced protection...');
    
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
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white !important;
            padding: 15px 20px !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            z-index: 2147483647 !important;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3) !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            max-width: 300px !important;
            word-wrap: break-word !important;
            animation: slideInWarning 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
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
    if (!document.getElementById('protection-styles')) {
        const style = document.createElement('style');
        style.id = 'protection-styles';
        style.textContent = `
            @keyframes slideInWarning {
                0% { transform: translateX(100%) scale(0.8); opacity: 0; }
                100% { transform: translateX(0) scale(1); opacity: 1; }
            }
            @keyframes slideOutWarning {
                0% { transform: translateX(0) scale(1); opacity: 1; }
                100% { transform: translateX(100%) scale(0.8); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Log security attempts
    function logAttempt(type, details = {}) {
        console.warn('🚨 Security attempt blocked:', type, details);
        
        // Send to server if available
        if (typeof fetch !== 'undefined' && document.querySelector('meta[name="csrf-token"]')) {
            fetch('/api/security-log', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    timestamp: new Date().toISOString(),
                    url: window.location.href,
                    userAgent: navigator.userAgent,
                    details: details
                })
            }).catch(() => {
                // Silently fail if endpoint not available
            });
        }
    }
    
    // Aggressive right-click protection
    const blockRightClick = function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        showWarning('🚫 Klik kanan dinonaktifkan untuk keamanan!');
        logAttempt('right_click_blocked', {
            element: e.target.tagName,
            className: e.target.className
        });
        return false;
    };
    
    // Add multiple event listeners for right-click
    document.addEventListener('contextmenu', blockRightClick, true);
    document.addEventListener('contextmenu', blockRightClick, false);
    window.addEventListener('contextmenu', blockRightClick, true);
    
    // Also block on document and body when ready
    if (document.body) {
        document.body.addEventListener('contextmenu', blockRightClick, true);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        if (document.body) {
            document.body.addEventListener('contextmenu', blockRightClick, true);
        }
        if (document.documentElement) {
            document.documentElement.addEventListener('contextmenu', blockRightClick, true);
        }
    });
    
    // Keyboard protection
    const blockKeyboard = function(e) {
        let blocked = false;
        let message = '';
        
        // F12
        if (e.keyCode === 123) {
            blocked = true;
            message = '🚫 F12 dinonaktifkan!';
        }
        
        // Ctrl+Shift+I (DevTools)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
            blocked = true;
            message = '🚫 Developer Tools dinonaktifkan!';
        }
        
        // Ctrl+Shift+J (Console)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 74) {
            blocked = true;
            message = '🚫 Console dinonaktifkan!';
        }
        
        // Ctrl+Shift+C (Inspector)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 67) {
            blocked = true;
            message = '🚫 Inspector dinonaktifkan!';
        }
        
        // Ctrl+U (View Source)
        if (e.ctrlKey && e.keyCode === 85) {
            blocked = true;
            message = '🚫 View Source dinonaktifkan!';
        }
        
        // Ctrl+S (Save)
        if (e.ctrlKey && e.keyCode === 83) {
            blocked = true;
            message = '🚫 Save Page dinonaktifkan!';
        }
        
        // Ctrl+P (Print)
        if (e.ctrlKey && e.keyCode === 80) {
            blocked = true;
            message = '🚫 Print dinonaktifkan!';
        }
        
        // Ctrl+A (Select All) - only block if not in input
        if (e.ctrlKey && e.keyCode === 65) {
            if (!isInputElement(e.target)) {
                blocked = true;
                message = '🚫 Select All dinonaktifkan!';
            }
        }
        
        // Ctrl+C (Copy) - only block if not in input
        if (e.ctrlKey && e.keyCode === 67) {
            if (!isInputElement(e.target)) {
                blocked = true;
                message = '🚫 Copy dinonaktifkan!';
            }
        }
        
        if (blocked) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            showWarning(message);
            logAttempt('keyboard_blocked', {
                keyCode: e.keyCode,
                ctrlKey: e.ctrlKey,
                shiftKey: e.shiftKey,
                altKey: e.altKey
            });
            return false;
        }
    };
    
    // Add keyboard protection
    document.addEventListener('keydown', blockKeyboard, true);
    window.addEventListener('keydown', blockKeyboard, true);
    
    // Check if element is input
    function isInputElement(element) {
        const inputTags = ['INPUT', 'TEXTAREA'];
        const inputTypes = ['text', 'password', 'email', 'number', 'search', 'tel', 'url'];
        
        if (inputTags.includes(element.tagName)) {
            if (element.tagName === 'INPUT') {
                return inputTypes.includes(element.type);
            }
            return true;
        }
        
        return element.contentEditable === 'true';
    }
    
    // Text selection protection
    const blockSelection = function(e) {
        if (!isInputElement(e.target)) {
            e.preventDefault();
            logAttempt('text_selection_blocked', {
                element: e.target.tagName
            });
            return false;
        }
    };
    
    document.addEventListener('selectstart', blockSelection, true);
    
    // Drag protection
    const blockDrag = function(e) {
        e.preventDefault();
        showWarning('🚫 Drag dinonaktifkan!');
        logAttempt('drag_blocked', {
            element: e.target.tagName
        });
        return false;
    };
    
    document.addEventListener('dragstart', blockDrag, true);
    
    // Print protection
    window.addEventListener('beforeprint', function(e) {
        e.preventDefault();
        showWarning('🚫 Print dinonaktifkan!');
        logAttempt('print_blocked');
        return false;
    });
    
    // DevTools detection
    let devToolsOpen = false;
    setInterval(function() {
        const threshold = 160;
        const widthThreshold = window.outerWidth - window.innerWidth > threshold;
        const heightThreshold = window.outerHeight - window.innerHeight > threshold;
        
        if ((widthThreshold || heightThreshold) && !devToolsOpen) {
            devToolsOpen = true;
            document.body.style.filter = 'blur(5px)';
            document.body.style.pointerEvents = 'none';
            showWarning('🚫 Developer Tools terdeteksi! Halaman diblur untuk keamanan.');
            logAttempt('devtools_detected', {
                outerWidth: window.outerWidth,
                innerWidth: window.innerWidth,
                outerHeight: window.outerHeight,
                innerHeight: window.innerHeight
            });
        } else if (!widthThreshold && !heightThreshold && devToolsOpen) {
            devToolsOpen = false;
            document.body.style.filter = 'none';
            document.body.style.pointerEvents = 'auto';
        }
    }, 500);
    
    // Console detection
    let consoleDetected = false;
    const originalConsole = window.console;
    Object.defineProperty(window, 'console', {
        get: function() {
            if (!consoleDetected) {
                consoleDetected = true;
                showWarning('🚫 Console access terdeteksi!');
                logAttempt('console_access_detected');
            }
            return originalConsole;
        }
    });
    
    // Add protection indicator
    setTimeout(function() {
        if (document.body) {
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
                font-weight: 500;
                z-index: 999998;
                opacity: 0.8;
                font-family: monospace;
                pointer-events: none;
            `;
            document.body.appendChild(indicator);
        }
    }, 1000);
    
    // Disable eval
    window.eval = function() {
        logAttempt('eval_blocked');
        throw new Error('eval() disabled for security');
    };
    
    // Override Function.prototype.toString
    const originalToString = Function.prototype.toString;
    Function.prototype.toString = function() {
        if (this === blockRightClick || this === blockKeyboard || this === blockSelection || this === blockDrag) {
            return 'function() { [native code] }';
        }
        return originalToString.call(this);
    };
    
    console.log('🔒 Enhanced protection activated successfully');
    
})();
