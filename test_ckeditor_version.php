<!DOCTYPE html>
<html>
<head>
    <title>CKEditor Version Test</title>
</head>
<body>
    <h1>CKEditor Version Test</h1>
    
    <h2>Testing CDN Version (4.25.1-lts)</h2>
    <textarea id="editor1" class="ckeditor" name="editor1">This is a test for CKEditor 4.25.1-lts from CDN</textarea>
    
    <h2>Testing NPM Version (4.25.1)</h2>
    <textarea id="editor2" class="ckeditor-npm" name="editor2">This is a test for CKEditor 4.25.1 from NPM</textarea>
    
    <script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
    <script>
        // Initialize CDN version
        CKEDITOR.replace('editor1');
        
        // Display version information
        document.addEventListener('DOMContentLoaded', function() {
            const versionInfo = document.createElement('div');
            versionInfo.innerHTML = '<h3>CDN CKEditor Version: ' + CKEDITOR.version + '</h3>';
            document.body.appendChild(versionInfo);
        });
    </script>
    
    <script type="module">
        // Import NPM version
        import CKEditor from '/node_modules/ckeditor4/ckeditor.js';
        
        // Initialize NPM version
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('editor2');
            
            const npmVersionInfo = document.createElement('div');
            npmVersionInfo.innerHTML = '<h3>NPM CKEditor Version: ' + CKEDITOR.version + '</h3>';
            document.body.appendChild(npmVersionInfo);
        }
    </script>
</body>
</html>