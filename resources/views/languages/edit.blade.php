@extends('layouts.app')

@section('title', 'Edit Language')
@section('breadcrumb', 'Edit Language')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Language</h1>
        <a href="{{ route('languages.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
            Back to Languages
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('languages.update', $language->code) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Language Code <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="code" 
                    name="code" 
                    value="{{ old('code', $language->code) }}"
                    class="w-full px-3 py-2 border border-gray-300  rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g., en, id, fr"
                    required
                >
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Use ISO 639-1 language codes (e.g., en for English, id for Indonesian)
                </p>
            </div>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700  mb-2">
                    Language Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $language->name) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 "
                    placeholder="e.g., English, Indonesian, French"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="is_default" 
                        name="is_default" 
                        value="1"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        {{ old('is_default', $language->is_default) ? 'checked' : '' }}
                    >
                    <label for="is_default" class="ml-2 block text-sm text-gray-700 ">
                        Set as Default Language
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    If checked, this will be the default language for the website. Only one language can be set as default.
                </p>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('languages.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700  bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Language
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
