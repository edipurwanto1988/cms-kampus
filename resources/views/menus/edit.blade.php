@extends('layouts.app')

@section('title', 'Edit Menu: ' . $menu->name)
@section('breadcrumb', 'Menus')

@section('content')
<div class="container mx-auto p-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">Edit Menu: {{ $menu->name }}</h1>
        <p class="text-gray-600">Configure your menu settings and link destination.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Panel: Menu Configuration -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h2 class="text-lg font-semibold mb-4">Menu Configuration</h2>
                
                <form id="editMenuForm" onsubmit="handleUpdateMenu(event)">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Menu Name</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ $menu->name }}"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" 
                                   name="location" 
                                   value="{{ $menu->location }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., header, footer">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                            <input type="text" 
                                   name="url" 
                                   value="{{ $menu->url }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., /home, https://example.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Link Target</label>
                            <select name="target" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="_self" {{ $menu->target === '_self' ? 'selected' : '' }}>Same Window</option>
                                <option value="_blank" {{ $menu->target === '_blank' ? 'selected' : '' }}>New Window</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ $menu->is_active ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                            <input type="number" 
                                   name="position" 
                                   value="{{ $menu->position }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="pt-4 border-t">
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Update Menu
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Translation Section -->
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold mb-4">Translations</h3>
                    <button onclick="showTranslationsModal()" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4">
                        <i class="fas fa-language mr-2"></i>Manage Translations
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Panel: Quick Links -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h2 class="text-lg font-semibold mb-4">Quick Links</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pages -->
                    <div>
                        <h3 class="font-medium mb-2">Pages</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @forelse($availableItems['pages'] as $page)
                                <div class="flex items-center justify-between p-2 border border-gray-200 rounded hover:bg-gray-50">
                                    <span class="text-sm">{{ $page['title'] }}</span>
                                    <button onclick="setUrl('{{ $page['url'] }}')" 
                                            class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1">
                                        Use URL
                                    </button>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No pages available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Posts -->
                    <div>
                        <h3 class="font-medium mb-2">Posts</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @forelse($availableItems['posts'] as $post)
                                <div class="flex items-center justify-between p-2 border border-gray-200 rounded hover:bg-gray-50">
                                    <span class="text-sm">{{ $post['title'] }}</span>
                                    <button onclick="setUrl('{{ $post['url'] }}')" 
                                            class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1">
                                        Use URL
                                    </button>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No posts available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="font-medium mb-2">Categories</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @forelse($availableItems['categories'] as $category)
                                <div class="flex items-center justify-between p-2 border border-gray-200 rounded hover:bg-gray-50">
                                    <span class="text-sm">{{ $category['title'] }}</span>
                                    <button onclick="setUrl('{{ $category['url'] }}')" 
                                            class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1">
                                        Use URL
                                    </button>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No categories available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Custom URLs -->
                    <div>
                        <h3 class="font-medium mb-2">Common URLs</h3>
                        <div class="space-y-2">
                            <button onclick="setUrl('/')" class="w-full text-left p-2 border border-gray-200 rounded hover:bg-gray-50 text-sm">
                                Home (/)
                            </button>
                            <button onclick="setUrl('/contact')" class="w-full text-left p-2 border border-gray-200 rounded hover:bg-gray-50 text-sm">
                                Contact (/contact)
                            </button>
                            <button onclick="setUrl('/about')" class="w-full text-left p-2 border border-gray-200 rounded hover:bg-gray-50 text-sm">
                                About (/about)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Translations Modal -->
<div id="translationsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Manage Translations</h2>
                <button onclick="hideTranslationsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="translationsForm" onsubmit="handleUpdateTranslations(event)">
                @csrf
                <div class="space-y-4">
                    @foreach($languages as $language)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $language->name }}</label>
                            <input type="text" 
                                   name="translations[{{ $language->code }}]" 
                                   value="{{ $menu->getTranslatedName($language->code) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ $language->name }} translation"
                                   @if($language->is_default) required @endif>
                        </div>
                    @endforeach
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="hideTranslationsModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Save Translations
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set URL helper function
function setUrl(url) {
    document.querySelector('input[name="url"]').value = url;
    showNotification('URL set to: ' + url, 'success');
}

// Modal functions
function showTranslationsModal() {
    document.getElementById('translationsModal').classList.remove('hidden');
}

function hideTranslationsModal() {
    document.getElementById('translationsModal').classList.add('hidden');
}

// Handle update menu
async function handleUpdateMenu(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        name: formData.get('name'),
        location: formData.get('location'),
        url: formData.get('url'),
        target: formData.get('target'),
        is_active: formData.get('is_active') === '1',
        position: parseInt(formData.get('position')) || 0
    };
    
    try {
        const response = await fetch("{{ route('menus.update', $menu) }}", {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            showNotification('Menu updated successfully!', 'success');
            setTimeout(() => window.location.href = "{{ route('menus.index') }}", 1000);
        }
    } catch (error) {
        console.error('Error updating menu:', error);
        showNotification('Error updating menu', 'error');
    }
}

// Handle update translations
async function handleUpdateTranslations(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        translations: {}
    };
    
    // Collect translations
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('translations[')) {
            const locale = key.match(/translations\[(.+)\]/)[1];
            data.translations[locale] = value;
        }
    }
    
    try {
        const response = await fetch("{{ route('menus.translations', $menu) }}", {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            hideTranslationsModal();
            showNotification('Translations updated successfully!', 'success');
        }
    } catch (error) {
        console.error('Error updating translations:', error);
        showNotification('Error updating translations', 'error');
    }
}

// Utility functions
function showNotification(message, type) {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
