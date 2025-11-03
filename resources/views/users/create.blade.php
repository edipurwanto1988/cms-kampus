@extends('layouts.app')

@section('title', 'Create User')
@section('breadcrumb', 'Create User')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create New User</h2>
        <p class="mt-1 text-sm text-gray-600">Add a new user to the system with appropriate roles</p>
    </div>

    <!-- Form -->
    <div class="card">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="Enter full name"
                           required>
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="form-input @error('email') border-red-500 @enderror"
                           placeholder="user@example.com"
                           required>
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input @error('password') border-red-500 @enderror"
                           placeholder="Enter password"
                           required>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-input @error('password_confirmation') border-red-500 @enderror"
                           placeholder="Confirm password"
                           required>
                    @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Roles -->
                <div>
                    <label class="form-label">Roles</label>
                    <p class="mt-1 text-sm text-gray-600">Select the roles for this user (at least one required)</p>
                    
                    <div class="mt-3 space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4">
                        @if ($roles->count() > 0)
                            @foreach ($roles as $role)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="role_{{ $role->id }}" 
                                       name="roles[]" 
                                       value="{{ $role->id }}"
                                       class="form-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                       {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                <label for="role_{{ $role->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $role->name }}
                                    <span class="text-gray-500">({{ $role->permissions->count() }} permissions)</span>
                                </label>
                            </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">No roles available. Please create roles first.</p>
                        @endif
                    </div>
                    @error('roles')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection