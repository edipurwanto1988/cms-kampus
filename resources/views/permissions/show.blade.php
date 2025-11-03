@extends('layouts.app')

@section('title', 'Permission Details')
@section('breadcrumb', 'Permission Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $permission->name }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ $permission->description ?? 'No description provided' }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('permissions.edit', $permission) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Edit Permission
            </a>
            <a href="{{ route('permissions.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Permissions
            </a>
        </div>
    </div>

    <!-- Permission Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="md:col-span-1">
            <div class="card">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Name</span>
                        <p class="text-sm text-gray-900">{{ $permission->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Slug</span>
                        <p class="text-sm text-gray-900">{{ $permission->slug }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Description</span>
                        <p class="text-sm text-gray-900">{{ $permission->description ?? 'No description' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Created</span>
                        <p class="text-sm text-gray-900">{{ $permission->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                        <p class="text-sm text-gray-900">{{ $permission->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles -->
        <div class="md:col-span-2">
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Roles with this Permission ({{ $permission->roles->count() }})</h3>
                    <a href="{{ route('roles.index') }}" class="text-sm text-primary-600 hover:text-primary-900">
                        <i class="fas fa-shield-alt mr-1"></i>Manage Roles
                    </a>
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
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $role->name }}</p>
                                <p class="text-xs text-gray-500">{{ $role->users_count ?? $role->users()->count() }} users</p>
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
                        <p class="text-sm text-gray-500">No roles assigned to this permission</p>
                        <a href="{{ route('roles.index') }}" class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-900">
                            <i class="fas fa-plus mr-1"></i>Assign to Roles
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Users with this Permission -->
    <div class="mt-6">
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Users with this Permission</h3>
                @if ($permission->roles->count() > 0)
                    <span class="text-sm text-gray-500">
                        Total Users: {{ $permission->roles->sum(function($role) { return $role->users_count ?? $role->users()->count(); }) }}
                    </span>
                @endif
            </div>
            
            @if ($permission->roles->count() > 0)
                <div class="space-y-4">
                    @foreach ($permission->roles as $role)
                        @if ($role->users->count() > 0)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $role->name }} ({{ $role->users->count() }} users)</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    User
                                                </th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Email
                                                </th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($role->users as $user)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-6 w-6">
                                                            <div class="h-6 w-6 rounded-full bg-primary-600 flex items-center justify-center">
                                                                <span class="text-white font-medium text-xs">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                            @if ($user->id === Auth::id())
                                                            <div class="text-xs text-gray-500">You</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('users.show', $user) }}" class="text-primary-600 hover:text-primary-900">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-400 text-3xl mb-3"></i>
                    <p class="text-sm text-gray-500">No users have this permission</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection