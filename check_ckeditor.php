<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CKEditor Test</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-6">CKEditor Test</h1>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">CKEditor Test</h2>
            
            <div class="mb-4">
                <label for="test_content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                <textarea id="test_content" name="content" class="ckeditor w-full border border-gray-300 rounded-md p-2" rows="10">This is test content for CKEditor.</textarea>
            </div>
            
            <div class="mb-4">
                <button onclick="checkCKEditor()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Check CKEditor Status
                </button>
                <button onclick="initializeCKEditor()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded ml-2">
                    Initialize CKEditor
                </button>
            </div>
            
            <div id="status" class="p-4 bg-gray-100 rounded-md">
                <p>Click "Check CKEditor Status" to see if CKEditor is loaded.</p>
            </div>
        </div>
    </div>
    
    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
    
    <script>
        function checkCKEditor() {
            const statusDiv = document.getElementById('status');
            
            if (typeof CKEDITOR !== 'undefined') {
                statusDiv.innerHTML = `
                    <p class="text-green-600 font-semibold">✓ CKEditor is loaded!</p>
                    <p>Version: ${CKEDITOR.version}</p>
                    <p>Revision: ${CKEDITOR.revision}</p>
                    <p>Instances: ${Object.keys(CKEDITOR.instances).length}</p>
                `;
                
                // Check if our textarea is replaced
                if (CKEDITOR.instances.test_content) {
                    statusDiv.innerHTML += '<p class="text-green-600">✓ Textarea is replaced with CKEditor!</p>';
                } else {
                    statusDiv.innerHTML += '<p class="text-yellow-600">⚠ Textarea is not yet replaced with CKEditor.</p>';
                }
            } else {
                statusDiv.innerHTML = '<p class="text-red-600 font-semibold">✗ CKEditor is not loaded!</p>';
            }
        }
        
        function initializeCKEditor() {
            const statusDiv = document.getElementById('status');
            
            if (typeof CKEDITOR !== 'undefined') {
                try {
                    CKEDITOR.replace('test_content');
                    statusDiv.innerHTML = '<p class="text-green-600 font-semibold">✓ CKEditor initialized successfully!</p>';
                } catch (error) {
                    statusDiv.innerHTML = `<p class="text-red-600 font-semibold">✗ Error initializing CKEditor: ${error.message}</p>`;
                }
            } else {
                statusDiv.innerHTML = '<p class="text-red-600 font-semibold">✗ CKEditor is not available!</p>';
            }
        }
        
        // Auto-initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            
            // Wait a bit for CKEditor to load
            setTimeout(function() {
                if (typeof CKEDITOR !== 'undefined') {
                    console.log('CKEditor is available, initializing...');
                    CKEDITOR.replace('test_content');
                } else {
                    console.log('CKEditor is not available');
                }
            }, 1000);
        });
    </script>
</body>
</html>