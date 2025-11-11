@extends('layouts.app')

@section('title', 'Edit Menu: ' . $menu->name)
@section('breadcrumb', 'Menus')

@push('scripts')
<!-- SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

@section('content')
<div class="container mx-auto p-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">Edit Menu: {{ $menu->name }}</h1>
        <p class="text-gray-600">Drag items from the left panel to add them to your menu, or drag items in the menu to reorder them.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Panel: Available Items -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h2 class="text-lg font-semibold mb-4">Add Menu Items</h2>
                
                <!-- Tabs for different item types -->
                <div class="mb-4 border-b border-gray-200">
                    <div class="flex space-x-2">
                        <button onclick="switchTab('pages')" id="tab-pages" class="tab-button active px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600">
                            Pages
                        </button>
                        <button onclick="switchTab('posts')" id="tab-posts" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Posts
                        </button>
                        <button onclick="switchTab('categories')" id="tab-categories" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Categories
                        </button>
                        <button onclick="switchTab('custom')" id="tab-custom" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Custom
                        </button>
                    </div>
                </div>

                <!-- Pages Tab -->
                <div id="panel-pages" class="tab-panel">
                    <div class="space-y-2 max-h-96 overflow-y-auto" id="pages-source">
                        @forelse($availableItems['pages'] as $page)
                            <div class="menu-item-source flex items-center justify-between p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-move"
                                 data-type="page"
                                 data-title="{{ $page['title'] }}"
                                 data-url="{{ $page['url'] }}"
                                 draggable="true">
                                <span class="text-sm">{{ $page['title'] }}</span>
                                <button onclick="addMenuItem('page', '{{ addslashes($page['title']) }}', '{{ $page['url'] }}')"
                                        class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1">
                                    Add
                                </button>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No pages available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Posts Tab -->
                <div id="panel-posts" class="tab-panel hidden">
                    <div class="space-y-2 max-h-96 overflow-y-auto" id="posts-source">
                        @forelse($availableItems['posts'] as $post)
                            <div class="menu-item-source flex items-center justify-between p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-move"
                                 data-type="post"
                                 data-title="{{ $post['title'] }}"
                                 data-url="{{ $post['url'] }}"
                                 draggable="true">
                                <span class="text-sm">{{ $post['title'] }}</span>
                                <button onclick="addMenuItem('post', '{{ addslashes($post['title']) }}', '{{ $post['url'] }}')"
                                        class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1">
                                    Add
                                </button>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No posts available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Categories Tab -->
                <div id="panel-categories" class="tab-panel hidden">
                    <div class="space-y-2 max-h-96 overflow-y-auto" id="categories-source">
                        @forelse($availableItems['categories'] as $category)
                            <div class="menu-item-source flex items-center justify-between p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-move"
                                 data-type="category"
                                 data-title="{{ $category['title'] }}"
                                 data-url="{{ $category['url'] }}"
                                 draggable="true">
                                <span class="text-sm">{{ $category['title'] }}</span>
                                <button onclick="addMenuItem('category', '{{ addslashes($category['title']) }}', '{{ $category['url'] }}')"
                                        class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1">
                                    Add
                                </button>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No categories available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Custom Link Tab -->
                <div id="panel-custom" class="tab-panel hidden">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Navigation Label</label>
                            <input type="text" id="custom-title" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Link Text">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                            <input type="text" id="custom-url" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="https://example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Link Target</label>
                            <select id="custom-target" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <option value="_self">Same Window</option>
                                <option value="_blank">New Window</option>
                            </select>
                        </div>
                        <button onclick="addCustomMenuItem()" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            Add to Menu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Menu Structure -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Menu Structure</h2>
                    <button onclick="saveMenuStructure()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                        <i class="fas fa-save mr-2"></i>Save Menu
                    </button>
                </div>

                <div id="menu-structure" class="min-h-64">
                    @if($menu->items->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Drag items here or click "Add" to build your menu</p>
                        </div>
                    @else
                        <ul id="menu-list" class="menu-list space-y-1">
                            @foreach($menu->items as $item)
                                @include('menus.partials.menu-item', ['item' => $item, 'level' => 0])
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let menuItems = {};
let sortables = {};

// Initialize menu items data
@foreach($menu->items as $item)
    menuItems[{{ $item->id }}] = {
        id: {{ $item->id }},
        title: @json($item->title),
        url: @json($item->url),
        target: @json($item->target ?? '_self'),
        is_active: {{ $item->is_active ? 'true' : 'false' }},
        parent_id: null
    };
    @if($item->children->count() > 0)
        @foreach($item->children as $child)
            menuItems[{{ $child->id }}] = {
                id: {{ $child->id }},
                title: @json($child->title),
                url: @json($child->url),
                target: @json($child->target ?? '_self'),
                is_active: {{ $child->is_active ? 'true' : 'false' }},
                parent_id: {{ $item->id }}
            };
        @endforeach
    @endif
@endforeach

// Tab switching
function switchTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'text-blue-600', 'border-blue-600');
        button.classList.add('text-gray-500');
    });
    
    // Show selected panel
    document.getElementById('panel-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'text-blue-600', 'border-blue-600');
    activeTab.classList.remove('text-gray-500');
}

// Add menu item
async function addMenuItem(type, title, url, target = '_self') {
    try {
        const response = await fetch("{{ route('menus.items.add', $menu) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                type: type,
                title: title,
                url: url,
                target: target
            })
        });

        const data = await response.json();
        
        if (data.status === 'ok') {
            // Add to menu structure
            const menuList = document.getElementById('menu-list');
            if (!menuList) {
                // Create menu list if it doesn't exist
                const structureDiv = document.getElementById('menu-structure');
                structureDiv.innerHTML = '<ul id="menu-list" class="menu-list space-y-1"></ul>';
            }
            
            const newItem = createMenuItemElement(data.item);
            document.getElementById('menu-list').appendChild(newItem);
            
            // Store in menuItems
            menuItems[data.item.id] = data.item;
            
            // Reinitialize sortable
            initializeSortable();
            
            showNotification('Item added to menu', 'success');
        }
    } catch (error) {
        console.error('Error adding menu item:', error);
        showNotification('Error adding menu item', 'error');
    }
}

// Add custom menu item
async function addCustomMenuItem() {
    const title = document.getElementById('custom-title').value;
    const url = document.getElementById('custom-url').value;
    const target = document.getElementById('custom-target').value;
    
    if (!title || !url) {
        showNotification('Please fill in both title and URL', 'error');
        return;
    }
    
    await addMenuItem('custom', title, url, target);
    
    // Clear form
    document.getElementById('custom-title').value = '';
    document.getElementById('custom-url').value = '';
}

// Create menu item element
function createMenuItemElement(item) {
    const li = document.createElement('li');
    li.className = 'menu-item bg-gray-50 border border-gray-200 rounded p-3 cursor-move';
    li.dataset.id = item.id;
    li.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center flex-1">
                <i class="fas fa-grip-vertical text-gray-400 mr-2"></i>
                <div class="flex-1">
                    <div class="font-medium text-sm">${escapeHtml(item.title)}</div>
                    <div class="text-xs text-gray-500">${escapeHtml(item.url)}</div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="editMenuItem(${item.id})" class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteMenuItem(${item.id})" class="text-red-600 hover:text-red-800 text-sm px-2 py-1">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <ul class="menu-children mt-2 ml-6 space-y-1"></ul>
    `;
    return li;
}

// Initialize SortableJS
function initializeSortable() {
    // Destroy existing sortables
    Object.values(sortables).forEach(sortable => {
        if (sortable) sortable.destroy();
    });
    sortables = {};
    
    // Initialize sortable for main menu list
    const menuList = document.getElementById('menu-list');
    if (menuList) {
        sortables['menu-list'] = new Sortable(menuList, {
            animation: 150,
            group: {
                name: 'nested',
                pull: true,
                put: function(to, from, item) {
                    // Prevent putting items directly into the main menu if they're being dragged from children
                    return to.el === menuList;
                }
            },
            sort: true,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                handleDragEnd(evt);
                // Reinitialize to update children lists
                setTimeout(() => initializeSortable(), 100);
            }
        });
    }
    
    // Initialize sortable for each children list
    document.querySelectorAll('.menu-children').forEach((childrenList) => {
        const parentItem = childrenList.closest('.menu-item');
        if (parentItem) {
            const parentId = parentItem.dataset.id;
            sortables[`children-${parentId}`] = new Sortable(childrenList, {
                animation: 150,
                group: {
                    name: 'nested',
                    pull: true,
                    put: true
                },
                sort: true,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    handleDragEnd(evt);
                    // Reinitialize to update children lists
                    setTimeout(() => initializeSortable(), 100);
                }
            });
        }
    });
}

// Handle drag end event
function handleDragEnd(evt) {
    const item = evt.item;
    const parentList = item.parentElement;
    const parentItem = parentList.closest('.menu-item');
    
    // Update visual hierarchy
    updateVisualHierarchy(item, parentItem);
    
    // Update menu structure
    updateMenuStructure();
}

// Update visual hierarchy
function updateVisualHierarchy(item, parentItem) {
    // Remove any existing level classes
    item.classList.remove('level-0', 'level-1', 'level-2', 'level-3');
    
    if (parentItem) {
        // Item is now a child
        const level = getItemLevel(parentItem) + 1;
        item.classList.add(`level-${Math.min(level, 3)}`);
        
        // Ensure parent has children list
        let childrenList = parentItem.querySelector('.menu-children');
        if (!childrenList) {
            childrenList = document.createElement('ul');
            childrenList.className = 'menu-children mt-2 ml-6 space-y-1';
            childrenList.style.minHeight = '10px';
            parentItem.appendChild(childrenList);
        }
    } else {
        // Item is now at root level
        item.classList.add('level-0');
    }
}

// Get item level
function getItemLevel(item) {
    if (!item || !item.classList) return 0;
    
    for (let i = 0; i <= 3; i++) {
        if (item.classList.contains(`level-${i}`)) {
            return i;
        }
    }
    return 0;
}

// Update menu structure
function updateMenuStructure() {
    const menuList = document.getElementById('menu-list');
    if (!menuList) return;
    
    // Update visual hierarchy for all items
    Array.from(menuList.children).forEach(li => {
        updateVisualHierarchy(li, null);
        updateChildrenHierarchy(li);
    });
}

// Update children hierarchy recursively
function updateChildrenHierarchy(parentItem) {
    const childrenList = parentItem.querySelector('.menu-children');
    if (childrenList && childrenList.children.length > 0) {
        Array.from(childrenList.children).forEach(childLi => {
            updateVisualHierarchy(childLi, parentItem);
            updateChildrenHierarchy(childLi);
        });
    }
}

// Edit menu item
function editMenuItem(id) {
    const item = menuItems[id];
    if (!item) return;
    
    const title = prompt('Navigation Label:', item.title);
    if (title === null) return;
    
    const url = prompt('URL:', item.url);
    if (url === null) return;
    
    const target = prompt('Link Target (_self or _blank):', item.target || '_self');
    if (target === null) return;
    
    updateMenuItem(id, title, url, target);
}

// Update menu item
async function updateMenuItem(id, title, url, target) {
    try {
        const response = await fetch(`{{ route('menus.items.update', [$menu, ':id']) }}`.replace(':id', id), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                title: title,
                url: url,
                target: target || '_self'
            })
        });

        const data = await response.json();
        
        if (data.status === 'ok') {
            // Update in menuItems
            if (menuItems[id]) {
                menuItems[id].title = title;
                menuItems[id].url = url;
                menuItems[id].target = target || '_self';
            }
            
            // Update DOM
            const li = document.querySelector(`[data-id="${id}"]`);
            if (li) {
                const titleDiv = li.querySelector('.font-medium');
                const urlDiv = li.querySelector('.text-gray-500');
                if (titleDiv) titleDiv.textContent = title;
                if (urlDiv) urlDiv.textContent = url;
            }
            
            showNotification('Menu item updated', 'success');
        }
    } catch (error) {
        console.error('Error updating menu item:', error);
        showNotification('Error updating menu item', 'error');
    }
}

// Delete menu item
async function deleteMenuItem(id) {
    if (!confirm('Are you sure you want to delete this menu item? All its children will also be deleted.')) {
        return;
    }
    
    try {
        const response = await fetch(`{{ route('menus.items.delete', [$menu, ':id']) }}`.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.status === 'ok') {
            // Remove from DOM
            const li = document.querySelector(`[data-id="${id}"]`);
            if (li) {
                li.remove();
            }
            
            // Remove from menuItems
            delete menuItems[id];
            
            showNotification('Menu item deleted', 'success');
        }
    } catch (error) {
        console.error('Error deleting menu item:', error);
        showNotification('Error deleting menu item', 'error');
    }
}

// Save menu structure
async function saveMenuStructure() {
    const menuList = document.getElementById('menu-list');
    if (!menuList || menuList.children.length === 0) {
        showNotification('Menu is empty', 'error');
        return;
    }
    
    const structure = [];
    
    function buildStructure(li, parentId) {
        const id = parseInt(li.dataset.id);
        const item = {
            id: id,
            parent_id: parentId,
            children: []
        };
        
        const childrenList = li.querySelector('.menu-children');
        if (childrenList && childrenList.children.length > 0) {
            Array.from(childrenList.children).forEach(childLi => {
                item.children.push(buildStructure(childLi, id));
            });
        }
        
        return item;
    }
    
    Array.from(menuList.children).forEach((li, index) => {
        const item = buildStructure(li, null);
        item.position = index;
        structure.push(item);
    });
    
    try {
        const response = await fetch("{{ route('menus.structure', $menu) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                items: structure
            })
        });

        const data = await response.json();
        
        if (data.status === 'ok') {
            showNotification('Menu structure saved successfully!', 'success');
            // Reload page after a short delay to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    } catch (error) {
        console.error('Error saving menu structure:', error);
        showNotification('Error saving menu structure', 'error');
    }
}

// Utility functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeSortable();
    
    // Initialize drag from source items
    initializeSourceDraggable();
});

// Initialize source items drag and drop
function initializeSourceDraggable() {
    // Make source items draggable
    document.querySelectorAll('.menu-item-source').forEach(item => {
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.effectAllowed = 'copy';
            e.dataTransfer.setData('text/plain', JSON.stringify({
                type: this.dataset.type,
                title: this.dataset.title,
                url: this.dataset.url
            }));
            this.classList.add('opacity-50');
        });
        
        item.addEventListener('dragend', function(e) {
            this.classList.remove('opacity-50');
        });
    });
    
    // Setup drop zones for source items
    setupDropZones();
}

// Setup drop zones for source items
function setupDropZones() {
    const dropZones = document.querySelectorAll('#menu-list, .menu-children');
    
    dropZones.forEach(zone => {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'copy';
            this.classList.add('bg-blue-50', 'border-blue-300');
        });
        
        zone.addEventListener('dragleave', function(e) {
            this.classList.remove('bg-blue-50', 'border-blue-300');
        });
        
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('bg-blue-50', 'border-blue-300');
            
            try {
                const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                if (data.type && data.title && data.url) {
                    addMenuItem(data.type, data.title, data.url);
                }
            } catch (error) {
                console.error('Error parsing dropped data:', error);
            }
        });
    });
}
</script>

<style>
.menu-item {
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.menu-item:hover {
    background-color: #f9fafb;
}

.menu-item.sortable-ghost {
    opacity: 0.4;
    background-color: #e0e7ff;
}

.menu-item.sortable-drag {
    opacity: 0.8;
    transform: rotate(2deg);
}

.tab-button.active {
    border-bottom-color: #2563eb;
}

.menu-children {
    min-height: 10px;
    border-left: 2px dashed #e5e7eb;
    margin-left: 12px;
    padding-left: 12px;
    transition: all 0.2s;
}

/* Level indicators */
.level-0 {
    border-left-color: #6b7280;
}

.level-1 {
    border-left-color: #3b82f6;
    background-color: #f0f9ff;
}

.level-2 {
    border-left-color: #8b5cf6;
    background-color: #faf5ff;
}

.level-3 {
    border-left-color: #ec4899;
    background-color: #fdf2f8;
}

/* Drag and drop zones */
.menu-children.sortable-ghost {
    background-color: #dbeafe;
    border-color: #3b82f6;
}

/* Source items */
.menu-item-source {
    transition: all 0.2s;
}

.menu-item-source:hover {
    background-color: #f3f4f6;
    transform: translateX(2px);
}

.menu-item-source.opacity-50 {
    opacity: 0.5;
}

/* Drop zone feedback */
#menu-list.drag-over,
.menu-children.drag-over {
    background-color: #eff6ff;
    border-color: #3b82f6;
}

/* Improved visual feedback for nesting */
.menu-item:hover .menu-children {
    border-color: #9ca3af;
}

/* Animation for smooth transitions */
.menu-item {
    animation: slideIn 0.2s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Visual indicators for drag and drop */
.drag-over {
    border: 2px dashed #3b82f6 !important;
    background-color: #eff6ff !important;
}

/* Improved button styles */
.menu-item-source button {
    transition: all 0.2s;
}

.menu-item-source button:hover {
    transform: scale(1.05);
}
</style>
@endsection
