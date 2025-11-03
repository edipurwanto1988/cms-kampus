<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CKEditor Debug</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">CKEditor Debug</h1>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Network Status</h2>
            <div id="network-status" class="p-4 bg-gray-100 rounded">
                <p>Checking network status...</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">CKEditor Script Loading</h2>
            <div id="script-status" class="p-4 bg-gray-100 rounded">
                <p>Checking CKEditor script...</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Test Textarea</h2>
            <textarea id="test-editor" class="ckeditor w-full border border-gray-300 rounded p-2" rows="10">
                <p>This is test content for CKEditor.</p>
            </textarea>
            <div class="mt-4">
                <button onclick="initializeEditor()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Initialize CKEditor
                </button>
                <button onclick="checkEditor()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded ml-2">
                    Check Status
                </button>
            </div>
            <div id="editor-status" class="mt-4 p-4 bg-gray-100 rounded">
                <p>Click "Initialize CKEditor" to start.</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Console Log</h2>
            <div id="console-log" class="p-4 bg-gray-900 text-green-400 rounded font-mono text-sm h-64 overflow-y-auto">
                <p>Console output will appear here...</p>
            </div>
        </div>
    </div>
    
    <script>
        // Override console.log to display on page
        const originalLog = console.log;
        const originalError = console.error;
        const originalWarn = console.warn;
        
        function addToConsole(message, type = 'log') {
            const logDiv = document.getElementById('console-log');
            const timestamp = new Date().toLocaleTimeString();
            const color = type === 'error' ? 'text-red-400' : type === 'warn' ? 'text-yellow-400' : 'text-green-400';
            logDiv.innerHTML += `<p>[${timestamp}] <span class="${color}">${message}</span></p>`;
            logDiv.scrollTop = logDiv.scrollHeight;
        }
        
        console.log = function(...args) {
            originalLog.apply(console, args);
            addToConsole(args.join(' '), 'log');
        };
        
        console.error = function(...args) {
            originalError.apply(console, args);
            addToConsole(args.join(' '), 'error');
        };
        
        console.warn = function(...args) {
            originalWarn.apply(console, args);
            addToConsole(args.join(' '), 'warn');
        };
        
        // Check network status
        function checkNetwork() {
            const statusDiv = document.getElementById('network-status');
            
            // Check if we can reach the CKEditor CDN
            fetch('https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js', { method: 'HEAD' })
                .then(response => {
                    if (response.ok) {
                        statusDiv.innerHTML = '<p class="text-green-600">✓ CKEditor CDN is accessible</p>';
                    } else {
                        statusDiv.innerHTML = `<p class="text-red-600">✗ CKEditor CDN returned status: ${response.status}</p>`;
                    }
                })
                .catch(error => {
                    statusDiv.innerHTML = `<p class="text-red-600">✗ Network error: ${error.message}</p>`;
                });
        }
        
        // Check if CKEditor script loaded
        function checkScript() {
            const statusDiv = document.getElementById('script-status');
            
            if (typeof CKEDITOR !== 'undefined') {
                statusDiv.innerHTML = `
                    <p class="text-green-600">✓ CKEditor is loaded</p>
                    <p>Version: ${CKEDITOR.version}</p>
                    <p>Revision: ${CKEDITOR.revision}</p>
                `;
            } else {
                statusDiv.innerHTML = '<p class="text-red-600">✗ CKEditor is not loaded</p>';
            }
        }
        
        // Initialize CKEditor
        function initializeEditor() {
            console.log('Attempting to initialize CKEditor...');
            
            if (typeof CKEDITOR !== 'undefined') {
                try {
                    if (CKEDITOR.instances['test-editor']) {
                        CKEDITOR.instances['test-editor'].destroy();
                    }
                    
                    const editor = CKEDITOR.replace('test-editor', {
                        height: 300,
                        width: '100%',
                        toolbar: 'Full'
                    });
                    
                    editor.on('instanceReady', function() {
                        console.log('CKEditor instance is ready!');
                        checkEditor();
                    });
                    
                    editor.on('error', function(event) {
                        console.error('CKEditor error:', event.data);
                    });
                    
                } catch (error) {
                    console.error('Error initializing CKEditor:', error);
                }
            } else {
                console.error('CKEditor is not available!');
            }
        }
        
        // Check editor status
        function checkEditor() {
            const statusDiv = document.getElementById('editor-status');
            
            if (typeof CKEDITOR !== 'undefined') {
                if (CKEDITOR.instances['test-editor']) {
                    const data = CKEDITOR.instances['test-editor'].getData();
                    statusDiv.innerHTML = `
                        <p class="text-green-600">✓ CKEditor instance is active</p>
                        <p>Content length: ${data.length} characters</p>
                    `;
                } else {
                    statusDiv.innerHTML = '<p class="text-yellow-600">⚠ CKEditor instance not found</p>';
                }
            } else {
                statusDiv.innerHTML = '<p class="text-red-600">✗ CKEditor is not loaded</p>';
            }
        }
        
        // Load CKEditor script
        function loadCKEditor() {
            console.log('Loading CKEditor script...');
            
            const script = document.createElement('script');
            script.src = 'https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js';
            script.onload = function() {
                console.log('CKEditor script loaded successfully');
                checkScript();
            };
            script.onerror = function() {
                console.error('Failed to load CKEditor script');
            };
            document.head.appendChild(script);
        }
        
        // Initialize checks
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            checkNetwork();
            loadCKEditor();
        });
    </script>
</body>
</html>