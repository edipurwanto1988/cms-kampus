@extends('layouts.app')

@section('title', 'Edit Role')
@section('breadcrumb', 'Edit Role')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Edit Role: {{ $role->name }}</h2>
        <p class="mt-1 text-sm text-gray-600">Update role information and permissions</p>
    </div>

    <!-- Form -->
    <div class="card">
        <form action="{{ route('roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $role->name) }}"
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="e.g., Administrator"
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
                              placeholder="Describe the role and its purpose">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions -->
                <div>
                    <label class="form-label">Permissions</label>
                    <p class="mt-1 text-sm text-gray-600">Select the permissions for this role</p>
                    
                    <div class="mt-3 space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4">
                        @if ($permissions->count() > 0)
                            @foreach ($permissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="permission_{{ $permission->id }}" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}"
                                       class="form-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                       {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $permission->name }}
                                    <span class="text-gray-500">({{ $permission->slug }})</span>
                                </label>
                            </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">No permissions available. Please create permissions first.</p>
                        @endif
                    </div>
                    @error('permissions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('roles.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Role
                </button>
            </div>
        </form>
    </div>

    <!-- Role Information -->
    <div class="mt-6 card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Role Information</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Slug:</span>
                <span class="text-gray-600">{{ $role->slug }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Created:</span>
                <span class="text-gray-600">{{ $role->created_at->format('d M Y') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Users Count:</span>
                <span class="text-gray-600">{{ $role->users_count ?? $role->users()->count() }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Permissions Count:</span>
                <span class="text-gray-600">{{ $role->permissions->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection