@extends('layouts.app')

@section('title', 'Edit Permission')
@section('breadcrumb', 'Edit Permission')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Edit Permission: {{ $permission->name }}</h2>
        <p class="mt-1 text-sm text-gray-600">Update permission information</p>
    </div>

    <!-- Form -->
    <div class="card">
        <form action="{{ route('permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $permission->name) }}"
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
                              placeholder="Describe what this permission allows users to do">{{ old('description', $permission->description) }}</textarea>
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
                    <i class="fas fa-save mr-2"></i>Update Permission
                </button>
            </div>
        </form>
    </div>

    <!-- Permission Information -->
    <div class="mt-6 card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Permission Information</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Slug:</span>
                <span class="text-gray-600">{{ $permission->slug }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Created:</span>
                <span class="text-gray-600">{{ $permission->created_at->format('d M Y') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Roles Count:</span>
                <span class="text-gray-600">{{ $permission->roles_count ?? $permission->roles()->count() }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Last Updated:</span>
                <span class="text-gray-600">{{ $permission->updated_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Roles with this Permission -->
    <div class="mt-6 card">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Roles with this Permission ({{ $permission->roles->count() }})</h3>
            @if ($permission->roles->count() > 0)
                <a href="{{ route('roles.index') }}" class="text-sm text-primary-600 hover:text-primary-900">
                    View All Roles
                </a>
            @endif
        </div>
        
        @if ($permission->roles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($permission->roles as $role)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shield-alt text-indigo-600 text-xs"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $role->name }}</p>
                        <p class="text-xs text-gray-500">{{ $role->users_count ?? $role->users()->count() }} users</p>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('roles.show', $role) }}" class="text-primary-600 hover:text-primary-900">
                            <i class="fas fa-eye text-sm"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-shield-alt text-gray-400 text-3xl mb-3"></i>
                <p class="text-sm text-gray-500">No roles assigned to this permission</p>
                <a href="{{ route('roles.index') }}" class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-900">
                    <i class="fas fa-plus mr-1"></i>Assign to Roles
                </a>
            </div>
        @endif
    </div>
</div>
@endsection