@extends('layouts.app')

@section('title', 'Edit User')
@section('breadcrumb', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Edit User: {{ $user->name }}</h2>
        <p class="mt-1 text-sm text-gray-600">Update user information and role assignments</p>
    </div>

    <!-- Form -->
    <div class="card">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
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
                           value="{{ old('email', $user->email) }}"
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
                           placeholder="Leave blank to keep current password">
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password</p>
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-input @error('password_confirmation') border-red-500 @enderror"
                           placeholder="Confirm new password">
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
                                       {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
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
                    <i class="fas fa-save mr-2"></i>Update User
                </button>
            </div>
        </form>
    </div>

    <!-- User Information -->
    <div class="mt-6 card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">User ID:</span>
                <span class="text-gray-600">{{ $user->id }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Created:</span>
                <span class="text-gray-600">{{ $user->created_at->format('d M Y') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Email Verified:</span>
                <span class="text-gray-600">{{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not verified' }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Last Updated:</span>
                <span class="text-gray-600">{{ $user->updated_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection