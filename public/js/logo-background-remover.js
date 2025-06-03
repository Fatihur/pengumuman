/**
 * Logo Background Remover
 * JavaScript solution to remove white/gray backgrounds from logos
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    function removeLogoBackground() {
        const schoolLogos = document.querySelectorAll('.school-logo, img[alt="Logo Sekolah"]');
        
        schoolLogos.forEach(function(logo) {
            // Method 1: Force CSS styles
            logo.style.background = 'transparent';
            logo.style.backgroundColor = 'transparent';
            logo.style.backgroundImage = 'none';
            logo.style.boxShadow = 'none';
            logo.style.border = 'none';
            logo.style.outline = 'none';
            logo.style.mixBlendMode = 'multiply';
            logo.style.filter = 'contrast(1.2) brightness(1.1)';
            
            // Method 2: Remove parent container backgrounds
            const container = logo.parentElement;
            if (container) {
                container.style.background = 'transparent';
                container.style.backgroundColor = 'transparent';
                container.style.backgroundImage = 'none';
                container.style.boxShadow = 'none';
                container.style.border = 'none';
                container.style.outline = 'none';
            }
            
            // Method 3: Canvas-based background removal (advanced)
            if (logo.complete && logo.naturalWidth > 0) {
                processImageBackground(logo);
            } else {
                logo.addEventListener('load', function() {
                    processImageBackground(logo);
                });
            }
        });
    }
    
    // Advanced canvas-based background removal
    function processImageBackground(img) {
        try {
            // Create canvas
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Set canvas size
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            
            // Draw image to canvas
            ctx.drawImage(img, 0, 0);
            
            // Get image data
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            
            // Remove white/light gray backgrounds
            for (let i = 0; i < data.length; i += 4) {
                const r = data[i];
                const g = data[i + 1];
                const b = data[i + 2];
                const alpha = data[i + 3];
                
                // Check if pixel is white or light gray
                const isWhiteish = r > 240 && g > 240 && b > 240;
                const isLightGray = r > 220 && g > 220 && b > 220 && Math.abs(r - g) < 10 && Math.abs(g - b) < 10;
                
                if (isWhiteish || isLightGray) {
                    // Make pixel transparent
                    data[i + 3] = 0;
                }
            }
            
            // Put modified image data back
            ctx.putImageData(imageData, 0, 0);
            
            // Convert canvas to data URL
            const dataURL = canvas.toDataURL('image/png');
            
            // Replace image source
            img.src = dataURL;
            
            // console.log('Background removed from school logo using canvas processing'); // Disabled to prevent console detection
            
        } catch (error) {
            console.warn('Canvas background removal failed:', error);
            // Fallback to CSS-only approach
            fallbackCSSRemoval(img);
        }
    }
    
    // Fallback CSS-only background removal
    function fallbackCSSRemoval(img) {
        // Try different blend modes
        const blendModes = ['multiply', 'darken', 'overlay', 'screen'];
        let currentMode = 0;
        
        function tryNextBlendMode() {
            if (currentMode < blendModes.length) {
                img.style.mixBlendMode = blendModes[currentMode];
                currentMode++;
                
                // Try next mode after a short delay
                setTimeout(tryNextBlendMode, 100);
            }
        }
        
        tryNextBlendMode();
        
        // Additional CSS fixes
        img.style.filter = 'contrast(1.3) brightness(1.1) saturate(1.1)';
        img.style.background = 'transparent';
        img.style.backgroundColor = 'rgba(0, 0, 0, 0)';
        
        // console.log('Applied fallback CSS background removal'); // Disabled to prevent console detection
    }
    
    // SVG filter approach
    function createSVGFilter() {
        // Check if filter already exists
        if (document.getElementById('remove-white-filter')) {
            return;
        }
        
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.style.position = 'absolute';
        svg.style.width = '0';
        svg.style.height = '0';
        svg.innerHTML = `
            <defs>
                <filter id="remove-white-filter" x="0%" y="0%" width="100%" height="100%">
                    <feColorMatrix type="matrix" values="
                        1 0 0 0 0
                        0 1 0 0 0
                        0 0 1 0 0
                        -1 -1 -1 1 0
                    "/>
                </filter>
            </defs>
        `;
        
        document.body.appendChild(svg);
        
        // Apply filter to school logos
        const schoolLogos = document.querySelectorAll('.school-logo');
        schoolLogos.forEach(function(logo) {
            logo.style.filter = 'url(#remove-white-filter) contrast(1.2) brightness(1.1)';
        });
        
        // console.log('SVG filter created and applied'); // Disabled to prevent console detection
    }
    
    // Observer to handle dynamically loaded images
    function observeLogoChanges() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            const logos = node.querySelectorAll ? node.querySelectorAll('.school-logo, img[alt="Logo Sekolah"]') : [];
                            logos.forEach(function(logo) {
                                removeLogoBackground();
                            });
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    // Initialize background removal
    function init() {
        // Remove backgrounds immediately
        removeLogoBackground();
        
        // Create SVG filter
        createSVGFilter();
        
        // Set up observer for dynamic content
        observeLogoChanges();
        
        // Re-run after a short delay to catch any late-loading images
        setTimeout(removeLogoBackground, 500);
        setTimeout(removeLogoBackground, 1000);
        setTimeout(removeLogoBackground, 2000);
        
        // console.log('Logo background remover initialized'); // Disabled to prevent console detection
    }
    
    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Also run on window load to catch any late-loading images
    window.addEventListener('load', function() {
        setTimeout(removeLogoBackground, 100);
    });
    
    // Expose functions globally for debugging
    window.logoBackgroundRemover = {
        remove: removeLogoBackground,
        process: processImageBackground,
        fallback: fallbackCSSRemoval,
        createFilter: createSVGFilter
    };
    
})();
