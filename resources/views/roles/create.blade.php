@extends('layouts.app')

@section('title', 'Create Role')
@section('breadcrumb', 'Create Role')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create New Role</h2>
        <p class="mt-1 text-sm text-gray-600">Define a new role with specific permissions</p>
    </div>

    <!-- Form -->
    <div class="card">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
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
                              placeholder="Describe the role and its purpose">{{ old('description') }}</textarea>
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
                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
                    <i class="fas fa-save mr-2"></i>Create Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection