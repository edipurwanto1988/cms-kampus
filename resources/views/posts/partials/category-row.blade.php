@php
    $translation = $category->translations->first();
    if (!$translation) {
        $translation = $category->translations()->where('locale', 'en')->first();
    }
    $name = $translation ? $translation->name : 'Untitled Category';
    $description = $translation ? $translation->description : '';
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
@endphp
<tr class="category-row hover:bg-gray-50" data-category-id="{{ $category->id }}" data-category-name="{{ $name }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <input type="radio" 
               name="category_selection" 
               value="{{ $category->id }}" 
               class="category-radio"
               @if(old('category_id') == $category->id) checked @endif>
    </td>
    <td class="px-6 py-4">
        <div class="text-sm font-medium text-gray-900">
            {!! $indent !!}{{ $name }}
            @if($category->children->count() > 0)
                <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                    {{ $category->children->count() }} subcategories
                </span>
            @endif
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="text-sm text-gray-500">
            {{ Str::limit($description, 50) }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($category->is_active)
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Active
            </span>
        @else
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                Inactive
            </span>
        @endif
    </td>
</tr>

<!-- Render child categories -->
@if($category->children->count() > 0)
    @foreach($category->children as $child)
        @include('posts.partials.category-row', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif