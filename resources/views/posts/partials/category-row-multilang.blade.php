@php
    $translation = $category->translations->where('locale', $language->code)->first();
    if (!$translation) {
        $translation = $category->translations->where('locale', 'en')->first();
    }
    $name = $translation ? $translation->name : 'Untitled Category';
    $slug = $translation ? $translation->slug : '';
    $description = $translation ? $translation->description : '';
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
            {{ $name }}
            @if(!$translation || $translation->locale !== $language->code)
                <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded" title="Translation not available in {{ $language->name }}">
                    No {{ $language->name }} translation
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