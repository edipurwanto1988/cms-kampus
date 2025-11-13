@extends('layouts.app')

@push('scripts')
<!-- SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

@section('content')
<div class="container mx-auto p-4">
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Menu Management</h1>
            <button onclick="showAddMenuModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New Menu
            </button>
        </div>
        
        <!-- Language Tabs -->
        <div class="border-b border-gray-200 mb-4">
            <div class="flex space-x-2">
                @foreach ($languages as $language)
                    <button onclick="switchLanguageTab('{{ $language->code }}')" 
                            id="lang-tab-{{ $language->code }}"
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200
                                   @if ($loop->first)
                                       border-b-2 border-blue-500 text-blue-600
                                   @else
                                       border-transparent text-gray-500 hover:text-gray-700
                                   @endif">
                        {{ $language->name }}
                        @if ($language->is_default)
                            <span class="ml-1 text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded">Default</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Menu List with Drag and Drop -->
    <div id="menu-container" class="space-y-2">
        @forelse($menus as $menu)
            <div class="menu-item bg-white border border-gray-200 rounded-lg p-4 shadow-sm cursor-move hover:shadow-md transition-shadow"
                 data-id="{{ $menu->id }}"
                 data-translations='@json($menu->translations->pluck('name', 'locale'))'>
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        <i class="fas fa-grip-vertical text-gray-400 mr-3"></i>
                        <div class="flex-1">
                            <h3 class="font-medium text-lg menu-name" data-locale="{{ app()->getLocale() }}">
                                {{ $menu->name }}
                            </h3>
                            @if($menu->location)
                                <p class="text-sm text-gray-500">Location: {{ $menu->location }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="editMenuTranslations({{ $menu->id }})" 
                                class="text-blue-600 hover:text-blue-800 px-2 py-1"
                                title="Edit Translations">
                            <i class="fas fa-language"></i>
                        </button>
                        <a href="{{ route('menus.edit', $menu) }}"
                           class="text-green-600 hover:text-green-800 px-2 py-1"
                           title="Edit Menu">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteMenu({{ $menu->id }})" 
                                class="text-red-600 hover:text-red-800 px-2 py-1"
                                title="Delete Menu">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p class="text-lg">No menus found</p>
                <p class="text-sm">Click "Add New Menu" to create your first menu</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Menu Modal -->
<div id="addMenuModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Add New Menu</h2>
                <button onclick="hideAddMenuModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addMenuForm" onsubmit="handleAddMenu(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Menu Name</label>
                        <input type="text"
                               name="name"
                               id="menu-name"
                               required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter menu name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location (Optional)</label>
                        <input type="text"
                               name="location"
                               id="menu-location"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., header, footer">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL (Optional)</label>
                        <input type="text"
                               name="url"
                               id="menu-url"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., /home, https://example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link Target</label>
                        <select name="target" id="menu-target" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="_self">Same Window</option>
                            <option value="_blank">New Window</option>
                        </select>
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" id="menu-is-active" checked class="mr-2">
                            <span class="text-sm font-medium text-gray-700">Active</span>
                        </label>
                    </div>
                    
                    <!-- Translation Fields -->
                    <div class="border-t pt-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Translations</h3>
                        <div id="translation-fields" class="space-y-2">
                            @foreach ($languages as $language)
                                <div class="translation-field">
                                    <label class="block text-xs text-gray-600 mb-1">{{ $language->name }}</label>
                                    <input type="text" 
                                           name="translations[{{ $language->code }}]" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="{{ $language->name }} translation"
                                           @if($language->is_default) required @endif>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="hideAddMenuModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Create Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Translations Modal -->
<div id="editTranslationsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Edit Menu Translations</h2>
                <button onclick="hideEditTranslationsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editTranslationsForm" onsubmit="handleUpdateTranslations(event)">
                <div class="space-y-4">
                    <div id="edit-translation-fields" class="space-y-2">
                        <!-- Translation fields will be populated dynamically -->
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="hideEditTranslationsModal()" 
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
let currentLanguage = '{{ app()->getLocale() }}';
let editingMenuId = null;

// Language switching
function switchLanguageTab(languageCode) {
    currentLanguage = languageCode;
    
    // Update tab styles
    document.querySelectorAll('[id^="lang-tab-"]').forEach(tab => {
        tab.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeTab = document.getElementById('lang-tab-' + languageCode);
    activeTab.classList.add('border-b-2', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    
    // Update menu names display
    updateMenuNamesDisplay();
}

// Update menu names display based on current language
function updateMenuNamesDisplay() {
    document.querySelectorAll('.menu-name').forEach(element => {
        const menuItem = element.closest('.menu-item');
        const translations = JSON.parse(menuItem.dataset.translations);
        element.textContent = translations[currentLanguage] || translations['{{ config('app.fallback_locale', 'en') }}'] || element.textContent;
    });
}

// Modal functions
function showAddMenuModal() {
    document.getElementById('addMenuModal').classList.remove('hidden');
}

function hideAddMenuModal() {
    document.getElementById('addMenuModal').classList.add('hidden');
    document.getElementById('addMenuForm').reset();
}

function showEditTranslationsModal() {
    document.getElementById('editTranslationsModal').classList.remove('hidden');
}

function hideEditTranslationsModal() {
    document.getElementById('editTranslationsModal').classList.add('hidden');
    editingMenuId = null;
}

// Handle add menu
async function handleAddMenu(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        name: formData.get('name'),
        location: formData.get('location'),
        url: formData.get('url'),
        target: formData.get('target'),
        is_active: formData.get('is_active') === 'on',
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
        const response = await fetch("{{ route('menus.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            hideAddMenuModal();
            showNotification('Menu created successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        }
    } catch (error) {
        console.error('Error creating menu:', error);
        showNotification('Error creating menu', 'error');
    }
}

// Edit menu translations
function editMenuTranslations(menuId) {
    editingMenuId = menuId;
    const menuItem = document.querySelector(`[data-id="${menuId}"]`);
    const translations = JSON.parse(menuItem.dataset.translations);
    
    // Populate translation fields
    const container = document.getElementById('edit-translation-fields');
    container.innerHTML = '';
    
    const languages = @json($languages);
    languages.forEach(language => {
        const field = document.createElement('div');
        field.className = 'translation-field';
        field.innerHTML = `
            <label class="block text-xs text-gray-600 mb-1">${language.name}</label>
            <input type="text" 
                   name="translations[${language.code}]" 
                   value="${translations[language.code] || ''}"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="${language.name} translation"
                   ${language.is_default ? 'required' : ''}>
        `;
        container.appendChild(field);
    });
    
    showEditTranslationsModal();
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
        const response = await fetch(`{{ route('menus.translations', ':id') }}`.replace(':id', editingMenuId), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            hideEditTranslationsModal();
            showNotification('Translations updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        }
    } catch (error) {
        console.error('Error updating translations:', error);
        showNotification('Error updating translations', 'error');
    }
}

// Delete menu
async function deleteMenu(menuId) {
    if (!confirm('Are you sure you want to delete this menu? All menu items will also be deleted.')) {
        return;
    }
    
    try {
        const response = await fetch(`{{ route('menus.destroy', ':id') }}`.replace(':id', menuId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            showNotification('Menu deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        }
    } catch (error) {
        console.error('Error deleting menu:', error);
        showNotification('Error deleting menu', 'error');
    }
}

// Initialize drag and drop
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('menu-container');
    
    new Sortable(container, {
        animation: 150,
        handle: '.menu-item',
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            updateMenuOrder();
        }
    });
});

// Update menu order
async function updateMenuOrder() {
    const menuItems = document.querySelectorAll('.menu-item');
    const menus = [];
    
    menuItems.forEach((item, index) => {
        menus.push({
            id: parseInt(item.dataset.id),
            position: index
        });
    });
    
    try {
        const response = await fetch("{{ route('menus.order') }}", {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ menus: menus })
        });
        
        if (response.ok) {
            showNotification('Menu order updated!', 'success');
        }
    } catch (error) {
        console.error('Error updating menu order:', error);
        showNotification('Error updating menu order', 'error');
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

<style>
.menu-item {
    transition: all 0.2s;
}

.menu-item:hover {
    transform: translateY(-2px);
}

.menu-item.sortable-ghost {
    opacity: 0.4;
    background-color: #e0e7ff;
}

.menu-item.sortable-drag {
    opacity: 0.8;
    transform: rotate(2deg);
}

.translation-field input:focus {
    outline: none;
    ring: 2px;
    ring-color: #3b82f6;
}
</style>
@endsection
