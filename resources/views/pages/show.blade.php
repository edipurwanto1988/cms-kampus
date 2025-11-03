@extends('layouts.app')

@section('title', 'Page Details')
@section('breadcrumb', 'Page Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $page->title }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ $page->slug }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('pages.edit', $page) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Edit Page
            </a>
            <a href="{{ route('pages.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Pages
            </a>
        </div>
    </div>

    <!-- Page Details -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Page Content -->
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Page Content</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if ($page->getStatusColor() === 'green') bg-green-100 text-green-800
                        @elseif ($page->getStatusColor() === 'yellow') bg-yellow-100 text-yellow-800
                        @elseif ($page->getStatusColor() === 'blue') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $page->getStatusLabel() }}
                    </span>
                </div>
                
                @if ($page->excerpt)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Excerpt</h4>
                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $page->excerpt }}</p>
                </div>
                @endif
                
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Content</h4>
                    <div class="prose max-w-none">
                        @if ($page->content)
                            {!! $page->content !!}
                        @else
                            <p class="text-gray-500 italic">No content available</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="mt-6 card">
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Information</h3>
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Meta Title:</span>
                        <p class="text-sm text-gray-600">{{ $page->meta_title ?: 'Not set (uses page title)' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700">Meta Description:</span>
                        <p class="text-sm text-gray-600">{{ $page->meta_description ?: 'Not set' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Basic Information -->
            <div class="card">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Key Name</span>
                        <p class="text-sm text-gray-900">{{ $page->key_name }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Status</span>
                        <p class="text-sm text-gray-900">{{ $page->getStatusLabel() }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Active</span>
                        <p class="text-sm text-gray-900">{{ $page->is_active ? 'Yes' : 'No' }}</p>
                    </div>
                    @if ($page->published_at)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Published</span>
                        <p class="text-sm text-gray-900">{{ $page->published_at->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                    <div>
                        <span class="text-sm font-medium text-gray-500">Created</span>
                        <p class="text-sm text-gray-900">{{ $page->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Created By</span>
                        <p class="text-sm text-gray-900">{{ $page->creator ? $page->creator->name : 'System' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                        <p class="text-sm text-gray-900">{{ $page->updated_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Updated By</span>
                        <p class="text-sm text-gray-900">{{ $page->updater ? $page->updater->name : 'System' }}</p>
                    </div>
                </div>
            </div>

            <!-- Translations -->
            <div class="mt-6 card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Translations</h3>
                    <span class="text-sm text-gray-500">{{ $page->translations->count() }}</span>
                </div>
                
                @if ($page->translations->count() > 0)
                    <div class="space-y-2">
                        @foreach ($page->translations as $translation)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-medium text-xs">{{ strtoupper(substr($translation->locale, 0, 2)) }}</span>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $translation->title }}</p>
                                <p class="text-xs text-gray-500">{{ $translation->slug }}</p>
                            </div>
                            <div class="flex items-center space-x-1">
                                @if ($translation->human_reviewed)
                                <span class="text-green-600" title="Human Reviewed">
                                    <i class="fas fa-check-circle text-sm"></i>
                                </span>
                                @endif
                                @if ($translation->is_machine_translated)
                                <span class="text-blue-600" title="Machine Translated">
                                    <i class="fas fa-robot text-sm"></i>
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-language text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-500">No translations available</p>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-6 space-y-3">
                <a href="{{ route('pages.edit', $page) }}" class="w-full btn-primary text-center">
                    <i class="fas fa-edit mr-2"></i>Edit Page
                </a>
                <form action="{{ route('pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium text-sm">
                        <i class="fas fa-trash mr-2"></i>Delete Page
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection