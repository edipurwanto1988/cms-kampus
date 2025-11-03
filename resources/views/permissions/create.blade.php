@extends('layouts.app')

@section('title', 'Create Permission')
@section('breadcrumb', 'Create Permission')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create New Permission</h2>
        <p class="mt-1 text-sm text-gray-600">Define a new permission for role-based access control</p>
    </div>

    <!-- Form -->
    <div class="card">
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="e.g., manage_users"
                           required>
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="form-input @error('description') border-red-500 @enderror"
                              placeholder="Describe what this permission allows users to do">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('permissions.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Create Permission
                </button>
            </div>
        </form>
    </div>

    <!-- Common Permissions Guide -->
    <div class="mt-6 card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Common Permission Patterns</h3>
        <div class="space-y-3">
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700">Resource Management</p>
                <p class="text-xs text-gray-500 mt-1">Examples: create_users, edit_posts, delete_pages</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700">View Access</p>
                <p class="text-xs text-gray-500 mt-1">Examples: view_dashboard, view_reports, view_analytics</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700">System Administration</p>
                <p class="text-xs text-gray-500 mt-1">Examples: manage_settings, backup_system, view_logs</p>
            </div>
        </div>
    </div>
</div>
@endsection