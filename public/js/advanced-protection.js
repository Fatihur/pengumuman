/**
 * Advanced Client-side Protection
 * Multiple layers of obfuscation and protection
 */

// Obfuscated protection wrapper
(function(_0x4f8a,_0x2d1b){const _0x3c4e=_0x1a2b;function _0x1a2b(_0x4f8a,_0x2d1b){return _0x1a2b=function(_0x1a2b,_0x3c4e){_0x1a2b=_0x1a2b-0x0;var _0x4f8a=_0x4f8a[_0x1a2b];return _0x4f8a;},_0x1a2b(_0x4f8a,_0x2d1b);}while(!![]){try{const _0x2d1b=-parseInt(_0x3c4e(0x0))/0x1+-parseInt(_0x3c4e(0x1))/0x2*(parseInt(_0x3c4e(0x2))/0x3)+parseInt(_0x3c4e(0x3))/0x4+-parseInt(_0x3c4e(0x4))/0x5*(-parseInt(_0x3c4e(0x5))/0x6)+parseInt(_0x3c4e(0x6))/0x7+parseInt(_0x3c4e(0x7))/0x8+parseInt(_0x3c4e(0x8))/0x9;if(_0x2d1b===_0x4f8a)break;else _0x4f8a['push'](_0x4f8a['shift']());}catch(_0x1a2b){_0x4f8a['push'](_0x4f8a['shift']());}}}(['1|2|3|4|5|6|7|8|9'],0x12345));

// Anti-debugging techniques
(function() {
    'use strict';
    
    // Multiple protection layers
    const protectionLayers = {
        // Layer 1: Basic protection
        basic: function() {
            // Disable right click
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                showSecurityAlert('Right-click disabled');
                logSecurityEvent('right_click_blocked');
                return false;
            }, true);
            
            // Disable text selection
            document.addEventListener('selectstart', function(e) {
                if (!isAllowedElement(e.target)) {
                    e.preventDefault();
                    return false;
                }
            }, true);
            
            // Disable drag
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            }, true);
        },
        
        // Layer 2: Keyboard protection
        keyboard: function() {
            document.addEventListener('keydown', function(e) {
                // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, etc.
                if (e.keyCode === 123 || 
                    (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) ||
                    (e.ctrlKey && e.keyCode === 85) ||
                    (e.ctrlKey && e.keyCode === 83) ||
                    (e.ctrlKey && e.keyCode === 65) ||
                    (e.ctrlKey && e.keyCode === 67) ||
                    (e.ctrlKey && e.keyCode === 86) ||
                    (e.ctrlKey && e.keyCode === 88) ||
                    (e.ctrlKey && e.keyCode === 80)) {
                    
                    e.preventDefault();
                    e.stopPropagation();
                    showSecurityAlert('Keyboard shortcut blocked');
                    logSecurityEvent('keyboard_blocked', {key: e.keyCode});
                    return false;
                }
            }, true);
        },
        
        // Layer 3: DevTools detection
        devtools: function() {
            let devtools = {open: false, orientation: null};
            const threshold = 160;
            
            setInterval(function() {
                if (window.outerHeight - window.innerHeight > threshold || 
                    window.outerWidth - window.innerWidth > threshold) {
                    if (!devtools.open) {
                        devtools.open = true;
                        handleDevToolsDetection();
                    }
                } else {
                    devtools.open = false;
                    removeDevToolsBlur();
                }
            }, 500);
            
            // Console detection
            let consoleDetected = false;
            const originalConsole = window.console;
            Object.defineProperty(window, 'console', {
                get: function() {
                    if (!consoleDetected) {
                        consoleDetected = true;
                        handleDevToolsDetection();
                    }
                    return originalConsole;
                }
            });
        },
        
        // Layer 4: Advanced anti-debugging
        antiDebug: function() {
            // Debugger detection
            setInterval(function() {
                const start = performance.now();
                debugger;
                const end = performance.now();
                if (end - start > 100) {
                    handleDebuggerDetection();
                }
            }, 1000);
            
            // Function toString override
            const originalToString = Function.prototype.toString;
            Function.prototype.toString = function() {
                if (this === protectionLayers.basic || 
                    this === protectionLayers.keyboard ||
                    this === protectionLayers.devtools ||
                    this === protectionLayers.antiDebug) {
                    return 'function() { [native code] }';
                }
                return originalToString.call(this);
            };
            
            // Disable eval
            window.eval = function() {
                logSecurityEvent('eval_blocked');
                throw new Error('eval disabled');
            };
            
            // Override setTimeout/setInterval for protection
            const originalSetTimeout = window.setTimeout;
            const originalSetInterval = window.setInterval;
            
            window.setTimeout = function(func, delay) {
                if (typeof func === 'string') {
                    logSecurityEvent('settimeout_string_blocked');
                    return;
                }
                return originalSetTimeout.call(this, func, delay);
            };
            
            window.setInterval = function(func, delay) {
                if (typeof func === 'string') {
                    logSecurityEvent('setinterval_string_blocked');
                    return;
                }
                return originalSetInterval.call(this, func, delay);
            };
        },
        
        // Layer 5: Network monitoring
        network: function() {
            // Monitor fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                logSecurityEvent('fetch_monitored', {url: args[0]});
                return originalFetch.apply(this, args);
            };
            
            // Monitor XMLHttpRequest
            const originalXHR = window.XMLHttpRequest;
            window.XMLHttpRequest = function() {
                const xhr = new originalXHR();
                const originalOpen = xhr.open;
                xhr.open = function(method, url) {
                    logSecurityEvent('xhr_monitored', {method, url});
                    return originalOpen.apply(this, arguments);
                };
                return xhr;
            };
        }
    };
    
    // Utility functions
    function isAllowedElement(element) {
        const allowedTags = ['INPUT', 'TEXTAREA'];
        const allowedTypes = ['text', 'password', 'email', 'number', 'search'];
        
        if (allowedTags.includes(element.tagName)) {
            if (element.tagName === 'INPUT') {
                return allowedTypes.includes(element.type);
            }
            return true;
        }
        
        return element.contentEditable === 'true';
    }
    
    function showSecurityAlert(message) {
        // Create floating alert
        const alert = document.createElement('div');
        alert.className = 'security-alert';
        alert.textContent = message;
        alert.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            z-index: 999999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            }
        }, 3000);
    }
    
    function logSecurityEvent(type, data = {}) {
        const eventData = {
            type: type,
            timestamp: new Date().toISOString(),
            userAgent: navigator.userAgent,
            url: window.location.href,
            data: data
        };
        
        // Send to server
        if (typeof fetch !== 'undefined') {
            fetch('/api/security-log', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(eventData)
            }).catch(() => {});
        }
    }
    
    function handleDevToolsDetection() {
        document.body.classList.add('devtools-detected');
        showSecurityAlert('Developer tools detected');
        logSecurityEvent('devtools_detected');
        
        // Optional: redirect after delay
        // setTimeout(() => {
        //     window.location.href = '/';
        // }, 3000);
    }
    
    function removeDevToolsBlur() {
        document.body.classList.remove('devtools-detected');
    }
    
    function handleDebuggerDetection() {
        showSecurityAlert('Debugger detected');
        logSecurityEvent('debugger_detected');
        
        // Clear console
        if (console.clear) {
            console.clear();
        }
    }
    
    // Initialize all protection layers
    function initializeProtection() {
        try {
            // Add CSS animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
            
            // Initialize protection layers
            Object.values(protectionLayers).forEach(layer => {
                if (typeof layer === 'function') {
                    layer();
                }
            });
            
            // Add protection status indicator
            const statusIndicator = document.createElement('div');
            statusIndicator.textContent = '🔒 Protected';
            statusIndicator.style.cssText = `
                position: fixed;
                bottom: 10px;
                left: 10px;
                background: rgba(34, 197, 94, 0.9);
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 10px;
                z-index: 999998;
                opacity: 0.7;
            `;
            document.body.appendChild(statusIndicator);
            
            logSecurityEvent('protection_initialized');
            
        } catch (error) {
            console.error('Protection initialization failed:', error);
        }
    }
    
    // Start protection when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeProtection);
    } else {
        initializeProtection();
    }
    
    // Prevent script tampering
    Object.freeze(protectionLayers);
    Object.freeze(window.console);
    
    // Hide this script from inspection
    const scripts = document.getElementsByTagName('script');
    for (let script of scripts) {
        if (script.src.includes('advanced-protection.js')) {
            script.style.display = 'none';
        }
    }
    
})();

// Additional obfuscated layer
!function(){const e=()=>{document.addEventListener("keydown",e=>{(123===e.keyCode||e.ctrlKey&&e.shiftKey&&73===e.keyCode||e.ctrlKey&&e.shiftKey&&74===e.keyCode||e.ctrlKey&&85===e.keyCode)&&(e.preventDefault(),e.stopPropagation())},!0)};e()}();
