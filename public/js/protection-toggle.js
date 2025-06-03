/**
 * Protection Toggle Script
 * Allows admin to enable/disable protection features
 */

(function() {
    'use strict';
    
    // Protection toggle functionality
    const ProtectionToggle = {
        // Current protection state
        state: {
            rightClick: true,
            keyboard: true,
            textSelection: true,
            imageDrag: true,
            devTools: true,
            printScreen: true
        },
        
        // Initialize toggle controls
        init: function() {
            this.createTogglePanel();
            this.bindEvents();
            this.loadState();
        },
        
        // Create toggle control panel
        createTogglePanel: function() {
            // Only show for admin users or in development
            if (!this.isAdminOrDev()) return;
            
            const panel = document.createElement('div');
            panel.id = 'protection-toggle-panel';
            panel.style.cssText = `
                position: fixed;
                top: 10px;
                left: 10px;
                background: rgba(0, 0, 0, 0.9);
                color: white;
                padding: 15px;
                border-radius: 8px;
                font-family: Arial, sans-serif;
                font-size: 12px;
                z-index: 999997;
                min-width: 200px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                transition: transform 0.3s ease;
                transform: translateX(-180px);
            `;
            
            panel.innerHTML = `
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 10px;">
                    <h4 style="margin: 0; font-size: 14px; font-weight: bold;">🔒 Protection Controls</h4>
                    <button id="toggle-panel-btn" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px;">▶</button>
                </div>
                <div id="toggle-controls" style="display: none;">
                    <div class="toggle-item">
                        <label style="display: flex; align-items: center; margin-bottom: 8px; cursor: pointer;">
                            <input type="checkbox" id="toggle-right-click" checked style="margin-right: 8px;">
                            <span>Right-click Protection</span>
                        </label>
                    </div>
                    <div class="toggle-item">
                        <label style="display: flex; align-items: center; margin-bottom: 8px; cursor: pointer;">
                            <input type="checkbox" id="toggle-keyboard" checked style="margin-right: 8px;">
                            <span>Keyboard Shortcuts</span>
                        </label>
                    </div>
                    <div class="toggle-item">
                        <label style="display: flex; align-items: center; margin-bottom: 8px; cursor: pointer;">
                            <input type="checkbox" id="toggle-text-selection" checked style="margin-right: 8px;">
                            <span>Text Selection</span>
                        </label>
                    </div>
                    <div class="toggle-item">
                        <label style="display: flex; align-items: center; margin-bottom: 8px; cursor: pointer;">
                            <input type="checkbox" id="toggle-image-drag" checked style="margin-right: 8px;">
                            <span>Image Drag</span>
                        </label>
                    </div>
                    <div class="toggle-item">
                        <label style="display: flex; align-items: center; margin-bottom: 8px; cursor: pointer;">
                            <input type="checkbox" id="toggle-dev-tools" checked style="margin-right: 8px;">
                            <span>DevTools Detection</span>
                        </label>
                    </div>
                    <div class="toggle-item">
                        <label style="display: flex; align-items: center; margin-bottom: 8px; cursor: pointer;">
                            <input type="checkbox" id="toggle-print-screen" checked style="margin-right: 8px;">
                            <span>Print Screen</span>
                        </label>
                    </div>
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #333;">
                        <button id="reset-protection" style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 11px; margin-right: 5px;">Reset</button>
                        <button id="save-state" style="background: #10b981; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 11px;">Save</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(panel);
        },
        
        // Bind events to toggle controls
        bindEvents: function() {
            const panel = document.getElementById('protection-toggle-panel');
            if (!panel) return;
            
            // Panel toggle
            const toggleBtn = document.getElementById('toggle-panel-btn');
            const controls = document.getElementById('toggle-controls');
            
            toggleBtn.addEventListener('click', function() {
                const isVisible = controls.style.display !== 'none';
                controls.style.display = isVisible ? 'none' : 'block';
                toggleBtn.textContent = isVisible ? '▶' : '▼';
                panel.style.transform = isVisible ? 'translateX(-180px)' : 'translateX(0)';
            });
            
            // Individual toggles
            document.getElementById('toggle-right-click').addEventListener('change', (e) => {
                this.toggleRightClickProtection(e.target.checked);
            });
            
            document.getElementById('toggle-keyboard').addEventListener('change', (e) => {
                this.toggleKeyboardProtection(e.target.checked);
            });
            
            document.getElementById('toggle-text-selection').addEventListener('change', (e) => {
                this.toggleTextSelectionProtection(e.target.checked);
            });
            
            document.getElementById('toggle-image-drag').addEventListener('change', (e) => {
                this.toggleImageDragProtection(e.target.checked);
            });
            
            document.getElementById('toggle-dev-tools').addEventListener('change', (e) => {
                this.toggleDevToolsDetection(e.target.checked);
            });
            
            document.getElementById('toggle-print-screen').addEventListener('change', (e) => {
                this.togglePrintScreenProtection(e.target.checked);
            });
            
            // Control buttons
            document.getElementById('reset-protection').addEventListener('click', () => {
                this.resetProtection();
            });
            
            document.getElementById('save-state').addEventListener('click', () => {
                this.saveState();
            });
        },
        
        // Check if user is admin or in development
        isAdminOrDev: function() {
            return window.location.hostname === 'localhost' || 
                   window.location.hostname === '127.0.0.1' ||
                   window.location.search.includes('admin=1') ||
                   document.body.classList.contains('admin-page');
        },
        
        // Toggle right-click protection
        toggleRightClickProtection: function(enabled) {
            this.state.rightClick = enabled;
            
            if (enabled) {
                document.addEventListener('contextmenu', this.preventRightClick, true);
            } else {
                document.removeEventListener('contextmenu', this.preventRightClick, true);
            }
            
            this.logToggle('right_click', enabled);
        },
        
        // Toggle keyboard protection
        toggleKeyboardProtection: function(enabled) {
            this.state.keyboard = enabled;
            
            if (enabled) {
                document.addEventListener('keydown', this.preventKeyboard, true);
            } else {
                document.removeEventListener('keydown', this.preventKeyboard, true);
            }
            
            this.logToggle('keyboard', enabled);
        },
        
        // Toggle text selection protection
        toggleTextSelectionProtection: function(enabled) {
            this.state.textSelection = enabled;
            
            const style = document.getElementById('text-selection-style') || document.createElement('style');
            style.id = 'text-selection-style';
            
            if (enabled) {
                style.textContent = `
                    * {
                        -webkit-user-select: none !important;
                        -moz-user-select: none !important;
                        -ms-user-select: none !important;
                        user-select: none !important;
                    }
                    input, textarea, [contenteditable] {
                        -webkit-user-select: text !important;
                        -moz-user-select: text !important;
                        -ms-user-select: text !important;
                        user-select: text !important;
                    }
                `;
                document.head.appendChild(style);
            } else {
                if (style.parentNode) {
                    style.parentNode.removeChild(style);
                }
            }
            
            this.logToggle('text_selection', enabled);
        },
        
        // Toggle image drag protection
        toggleImageDragProtection: function(enabled) {
            this.state.imageDrag = enabled;
            
            if (enabled) {
                document.addEventListener('dragstart', this.preventImageDrag, true);
            } else {
                document.removeEventListener('dragstart', this.preventImageDrag, true);
            }
            
            this.logToggle('image_drag', enabled);
        },
        
        // Toggle DevTools detection
        toggleDevToolsDetection: function(enabled) {
            this.state.devTools = enabled;
            
            if (enabled) {
                this.startDevToolsDetection();
            } else {
                this.stopDevToolsDetection();
            }
            
            this.logToggle('dev_tools', enabled);
        },
        
        // Toggle print screen protection
        togglePrintScreenProtection: function(enabled) {
            this.state.printScreen = enabled;
            
            if (enabled) {
                document.addEventListener('keyup', this.preventPrintScreen, true);
            } else {
                document.removeEventListener('keyup', this.preventPrintScreen, true);
            }
            
            this.logToggle('print_screen', enabled);
        },
        
        // Event handlers
        preventRightClick: function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        },
        
        preventKeyboard: function(e) {
            const blockedKeys = [123]; // F12
            const blockedCombos = [
                {ctrl: true, shift: true, key: 73}, // Ctrl+Shift+I
                {ctrl: true, shift: true, key: 74}, // Ctrl+Shift+J
                {ctrl: true, key: 85}, // Ctrl+U
                {ctrl: true, key: 83}, // Ctrl+S
            ];
            
            const isBlocked = blockedKeys.includes(e.keyCode) ||
                blockedCombos.some(combo => 
                    (!combo.ctrl || e.ctrlKey) &&
                    (!combo.shift || e.shiftKey) &&
                    (!combo.key || e.keyCode === combo.key)
                );
            
            if (isBlocked) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        },
        
        preventImageDrag: function(e) {
            if (e.target.tagName === 'IMG') {
                e.preventDefault();
                return false;
            }
        },
        
        preventPrintScreen: function(e) {
            if (e.key === 'PrintScreen') {
                // Log print screen attempt
            }
        },
        
        // DevTools detection
        startDevToolsDetection: function() {
            if (this.devToolsInterval) return;
            
            this.devToolsInterval = setInterval(() => {
                const threshold = 160;
                if (window.outerHeight - window.innerHeight > threshold || 
                    window.outerWidth - window.innerWidth > threshold) {
                    document.body.classList.add('devtools-detected');
                } else {
                    document.body.classList.remove('devtools-detected');
                }
            }, 1000);
        },
        
        stopDevToolsDetection: function() {
            if (this.devToolsInterval) {
                clearInterval(this.devToolsInterval);
                this.devToolsInterval = null;
                document.body.classList.remove('devtools-detected');
            }
        },
        
        // Reset all protections
        resetProtection: function() {
            Object.keys(this.state).forEach(key => {
                this.state[key] = true;
                const checkbox = document.getElementById(`toggle-${key.replace(/([A-Z])/g, '-$1').toLowerCase()}`);
                if (checkbox) checkbox.checked = true;
            });
            
            this.applyAllProtections();
            this.showMessage('Protection reset to default settings', 'success');
        },
        
        // Apply all protections based on current state
        applyAllProtections: function() {
            this.toggleRightClickProtection(this.state.rightClick);
            this.toggleKeyboardProtection(this.state.keyboard);
            this.toggleTextSelectionProtection(this.state.textSelection);
            this.toggleImageDragProtection(this.state.imageDrag);
            this.toggleDevToolsDetection(this.state.devTools);
            this.togglePrintScreenProtection(this.state.printScreen);
        },
        
        // Save current state
        saveState: function() {
            localStorage.setItem('protectionState', JSON.stringify(this.state));
            this.showMessage('Protection settings saved', 'success');
        },
        
        // Load saved state
        loadState: function() {
            const saved = localStorage.getItem('protectionState');
            if (saved) {
                try {
                    this.state = {...this.state, ...JSON.parse(saved)};
                    this.updateCheckboxes();
                    this.applyAllProtections();
                } catch (e) {
                    console.warn('Failed to load protection state:', e);
                }
            }
        },
        
        // Update checkbox states
        updateCheckboxes: function() {
            Object.keys(this.state).forEach(key => {
                const checkbox = document.getElementById(`toggle-${key.replace(/([A-Z])/g, '-$1').toLowerCase()}`);
                if (checkbox) checkbox.checked = this.state[key];
            });
        },
        
        // Show message
        showMessage: function(message, type = 'info') {
            const msg = document.createElement('div');
            msg.style.cssText = `
                position: fixed;
                top: 50px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : '#3b82f6'};
                color: white;
                padding: 10px 15px;
                border-radius: 6px;
                font-size: 12px;
                z-index: 999998;
                animation: slideIn 0.3s ease-out;
            `;
            msg.textContent = message;
            document.body.appendChild(msg);
            
            setTimeout(() => {
                if (msg.parentNode) {
                    msg.parentNode.removeChild(msg);
                }
            }, 3000);
        },
        
        // Log toggle action
        logToggle: function(feature, enabled) {
            console.log(`Protection ${feature}: ${enabled ? 'enabled' : 'disabled'}`);
            
            // Send to server if logging endpoint exists
            if (typeof fetch !== 'undefined') {
                fetch('/api/security-log', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: 'protection_toggle',
                        timestamp: new Date().toISOString(),
                        details: { feature, enabled }
                    })
                }).catch(() => {});
            }
        }
    };
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => ProtectionToggle.init());
    } else {
        ProtectionToggle.init();
    }
    
    // Expose to global scope for debugging
    window.ProtectionToggle = ProtectionToggle;
    
})();
