@extends('layouts.app')

@section('title', 'Test CKEditor')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">CKEditor Test</h2>
        <p class="mt-1 text-sm text-gray-600">Testing CKEditor integration</p>
    </div>

    <div class="card">
        <form>
            <div class="space-y-4">
                <div>
                    <label for="test_title" class="form-label">Title</label>
                    <input type="text" id="test_title" name="title" class="form-input" placeholder="Enter title">
                </div>
                
                <div>
                    <label for="test_content" class="form-label">Content</label>
                    <textarea id="test_content" name="content" class="ckeditor form-input" rows="10" placeholder="Enter content">
                        <p>This is some <strong>sample content</strong> to test CKEditor.</p>
                        <p>You can format this text using the editor toolbar.</p>
                    </textarea>
                </div>
                
                <div>
                    <button type="button" onclick="checkCKEditor()" class="btn-secondary">
                        Check CKEditor Status
                    </button>
                    <button type="button" onclick="getCKEditorData()" class="btn-primary ml-2">
                        Get CKEditor Data
                    </button>
                </div>
                
                <div id="ckeditor_status" class="p-4 bg-gray-100 rounded-md">
                    <p>Click "Check CKEditor Status" to see if CKEditor is loaded.</p>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function checkCKEditor() {
    const statusDiv = document.getElementById('ckeditor_status');
    
    if (typeof CKEDITOR !== 'undefined') {
        let statusText = '<p class="text-green-600 font-semibold">✓ CKEditor is loaded!</p>';
        statusText += `<p>Version: ${CKEDITOR.version}</p>`;
        statusText += `<p>Instances: ${Object.keys(CKEDITOR.instances).length}</p>`;
        
        if (CKEDITOR.instances.test_content) {
            statusText += '<p class="text-green-600">✓ Textarea is replaced with CKEditor!</p>';
        } else {
            statusText += '<p class="text-yellow-600">⚠ Textarea is not yet replaced with CKEditor.</p>';
        }
        
        statusDiv.innerHTML = statusText;
    } else {
        statusDiv.innerHTML = '<p class="text-red-600 font-semibold">✗ CKEditor is not loaded!</p>';
    }
}

function getCKEditorData() {
    const statusDiv = document.getElementById('ckeditor_status');
    
    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.test_content) {
        const content = CKEDITOR.instances.test_content.getData();
        statusDiv.innerHTML = `
            <p class="text-green-600 font-semibold">✓ CKEditor Data:</p>
            <div class="mt-2 p-2 bg-white border border-gray-300 rounded">
                ${content}
            </div>
        `;
    } else {
        statusDiv.innerHTML = '<p class="text-red-600 font-semibold">✗ CKEditor instance not found!</p>';
    }
}

// Also check when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(checkCKEditor, 2000);
});
</script>
@endsection