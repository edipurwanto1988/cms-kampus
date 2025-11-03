import './bootstrap';

// Initialize CKEditor when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for CKEditor...');
    
    // Wait for CKEditor to be available
    const checkCKEditor = setInterval(function() {
        if (typeof CKEDITOR !== 'undefined') {
            clearInterval(checkCKEditor);
            console.log('CKEditor is available, initializing...');
            
            // Find all textarea elements with class 'ckeditor'
            const textareas = document.querySelectorAll('textarea.ckeditor');
            console.log('Found', textareas.length, 'textarea elements with ckeditor class');
            
            // Initialize CKEditor for each textarea (except 'content' which is handled in the view)
            textareas.forEach(function(textarea, index) {
                // Skip the content textarea as it's handled in the edit view
                if (textarea.id === 'content') {
                    console.log('Skipping content textarea - handled in view');
                    return;
                }
                
                // Make sure the textarea has an ID
                if (!textarea.id) {
                    textarea.id = 'ckeditor_' + index + '_' + Math.random().toString(36).substr(2, 9);
                }
                
                console.log('Initializing CKEditor for textarea with ID:', textarea.id);
                
                try {
                    // Replace the textarea with CKEditor
                    const editor = CKEDITOR.replace(textarea.id, {
                        height: 300,
                        width: '100%',
                        toolbar: 'Full'
                    });
                    
                    editor.on('instanceReady', function() {
                        console.log('CKEditor instance ready for:', textarea.id);
                    });
                } catch (error) {
                    console.error('Error initializing CKEditor for', textarea.id, ':', error);
                }
            });
        } else {
            console.log('CKEditor not yet available, waiting...');
        }
    }, 100);
    
    // Timeout after 10 seconds to prevent infinite checking
    setTimeout(function() {
        clearInterval(checkCKEditor);
        if (typeof CKEDITOR === 'undefined') {
            console.error('CKEditor failed to load within 10 seconds');
        }
    }, 10000);
});

// Also try to initialize CKEditor when window is fully loaded
window.addEventListener('load', function() {
    console.log('Window fully loaded, checking for CKEditor...');
    
    if (typeof CKEDITOR !== 'undefined') {
        // Find any textarea elements with class 'ckeditor' that haven't been replaced yet
        const textareas = document.querySelectorAll('textarea.ckeditor');
        
        textareas.forEach(function(textarea, index) {
            // Skip the content textarea as it's handled in the edit view
            if (textarea.id === 'content') {
                console.log('Skipping content textarea on window load - handled in view');
                return;
            }
            
            // Check if this textarea already has a CKEditor instance
            if (!CKEDITOR.instances[textarea.id]) {
                // Make sure the textarea has an ID
                if (!textarea.id) {
                    textarea.id = 'ckeditor_' + index + '_loaded_' + Math.random().toString(36).substr(2, 9);
                }
                
                console.log('Initializing CKEditor on window load for:', textarea.id);
                
                try {
                    CKEDITOR.replace(textarea.id, {
                        height: 300,
                        width: '100%',
                        toolbar: 'Full'
                    });
                } catch (error) {
                    console.error('Error initializing CKEditor on window load for', textarea.id, ':', error);
                }
            }
        });
    }
});
// Import Alpine.js
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

// Register Alpine plugins
Alpine.plugin(focus);

// Initialize Alpine
window.Alpine = Alpine;
Alpine.start();

// Common Alpine components
document.addEventListener('alpine:init', () => {
    // Dropdown component
    Alpine.data('dropdown', () => ({
        open: false,
        
        toggle() {
            this.open = !this.open;
        },
        
        close() {
            this.open = false;
        }
    }));
    
    // Sidebar component
    Alpine.data('sidebar', () => ({
        open: window.innerWidth >= 1024,
        
        toggle() {
            this.open = !this.open;
        },
        
        closeOnMobile() {
            if (window.innerWidth < 1024) {
                this.open = false;
            }
        }
    }));
    
    // Modal component
    Alpine.data('modal', () => ({
        open: false,
        
        show() {
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        
        hide() {
            this.open = false;
            document.body.style.overflow = '';
        }
    }));
});
