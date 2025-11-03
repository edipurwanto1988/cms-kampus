@extends('layouts.app')

@section('title', 'User Details')
@section('breadcrumb', 'User Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('users.edit', $user) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Edit User
            </a>
            <a href="{{ route('users.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>
    </div>

    <!-- User Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="md:col-span-1">
            <div class="card">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Name</span>
                        <p class="text-sm text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Email</span>
                        <p class="text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">User ID</span>
                        <p class="text-sm text-gray-900">#{{ $user->id }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Email Verified</span>
                        <p class="text-sm text-gray-900">
                            @if ($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>{{ $user->email_verified_at->format('d M Y') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Not verified
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Created</span>
                        <p class="text-sm text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                        <p class="text-sm text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles -->
        <div class="md:col-span-2">
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">User Roles ({{ $user->roles->count() }})</h3>
                    <a href="{{ route('users.edit', $user) }}#roles" class="text-sm text-primary-600 hover:text-primary-900">
                        <i class="fas fa-edit mr-1"></i>Manage Roles
                    </a>
                </div>
                
                @if ($user->roles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($user->roles as $role)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-indigo-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $role->name }}</p>
                                <p class="text-xs text-gray-500">{{ $role->permissions->count() }} permissions</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('roles.show', $role) }}" class="text-primary-600 hover:text-primary-900">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-shield-alt text-gray-400 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-500">No roles assigned to this user</p>
                        <a href="{{ route('users.edit', $user) }}" class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-900">
                            <i class="fas fa-plus mr-1"></i>Assign Roles
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Permissions -->
    <div class="mt-6">
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">All Permissions ({{ $user->permissions->count() }})</h3>
                <span class="text-sm text-gray-500">Combined from all assigned roles</span>
            </div>
            
            @if ($user->permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($user->permissions as $permission)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-key text-green-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $permission->name }}</p>
                            <p class="text-xs text-gray-500">{{ $permission->slug }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('permissions.show', $permission) }}" class="text-primary-600 hover:text-primary-900">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-key text-gray-400 text-3xl mb-3"></i>
                    <p class="text-sm text-gray-500">No permissions available</p>
                    <p class="text-xs text-gray-400 mt-1">Assign roles to this user to grant permissions</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    @if ($user->id !== Auth::id())
    <div class="mt-6 flex justify-end">
        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium text-sm">
                <i class="fas fa-trash mr-2"></i>Delete User
            </button>
        </form>
    </div>
    @endif
</div>
@endsection