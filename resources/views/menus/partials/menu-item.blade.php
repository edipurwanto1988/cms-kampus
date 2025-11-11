<li class="menu-item bg-gray-50 border border-gray-200 rounded p-3 cursor-move level-{{ $level }}" data-id="{{ $item->id }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center flex-1">
            <i class="fas fa-grip-vertical text-gray-400 mr-2"></i>
            <div class="flex-1">
                <div class="font-medium text-sm">{{ $item->title }}</div>
                <div class="text-xs text-gray-500">{{ $item->url }}</div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="editMenuItem({{ $item->id }})" class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button onclick="deleteMenuItem({{ $item->id }})" class="text-red-600 hover:text-red-800 text-sm px-2 py-1" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    @if($item->children->count() > 0)
        <ul class="menu-children mt-2 ml-6 space-y-1">
            @foreach($item->children as $child)
                @include('menus.partials.menu-item', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @else
        <ul class="menu-children mt-2 ml-6 space-y-1" style="min-height: 10px;"></ul>
    @endif
</li>

