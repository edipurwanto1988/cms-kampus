@extends('layouts.app')

@section('title', 'Pages')
@section('breadcrumb', 'Pages')

@section('content')
<!-- Header with Create Button -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-900">Page Management</h2>
    <a href="{{ route('pages.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>Add New Page
    </a>
</div>

<!-- Pages Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Page
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Key Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Translations
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created By
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($pages as $page)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $page->title }}</div>
                                <div class="text-xs text-gray-500">{{ $page->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $page->key_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if ($page->getStatusColor() === 'green') bg-green-100 text-green-800
                            @elseif ($page->getStatusColor() === 'yellow') bg-yellow-100 text-yellow-800
                            @elseif ($page->getStatusColor() === 'blue') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $page->getStatusLabel() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-900">{{ $page->translations_count }}</span>
                            @if ($page->translations_count > 0)
                            <div class="ml-2 flex -space-x-1">
                                @foreach ($page->translations->take(3) as $translation)
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium">
                                    {{ strtoupper(substr($translation->locale, 0, 2)) }}
                                </span>
                                @endforeach
                                @if ($page->translations_count > 3)
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-800 text-xs font-medium">
                                    +{{ $page->translations_count - 3 }}
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $page->creator ? $page->creator->name : 'System' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('pages.show', $page) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('pages.edit', $page) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this page? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        No pages found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if ($pages->hasPages())
    <div class="mt-6">
        {{ $pages->links() }}
    </div>
    @endif
</div>
@endsection