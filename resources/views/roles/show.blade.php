@extends('layouts.app')

@section('title', 'Role Details')
@section('breadcrumb', 'Role Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $role->name }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ $role->description ?? 'No description provided' }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('roles.edit', $role) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Edit Role
            </a>
            <a href="{{ route('roles.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Roles
            </a>
        </div>
    </div>

    <!-- Role Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="md:col-span-1">
            <div class="card">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Name</span>
                        <p class="text-sm text-gray-900">{{ $role->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Slug</span>
                        <p class="text-sm text-gray-900">{{ $role->slug }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Description</span>
                        <p class="text-sm text-gray-900">{{ $role->description ?? 'No description' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Created</span>
                        <p class="text-sm text-gray-900">{{ $role->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                        <p class="text-sm text-gray-900">{{ $role->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="md:col-span-2">
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Permissions ({{ $role->permissions->count() }})</h3>
                    <a href="{{ route('roles.edit', $role) }}#permissions" class="text-sm text-primary-600 hover:text-primary-900">
                        <i class="fas fa-edit mr-1"></i>Manage
                    </a>
                </div>
                
                @if ($role->permissions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($role->permissions as $permission)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-key text-green-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $permission->name }}</p>
                                <p class="text-xs text-gray-500">{{ $permission->slug }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-key text-gray-400 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-500">No permissions assigned to this role</p>
                        <a href="{{ route('roles.edit', $role) }}" class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-900">
                            <i class="fas fa-plus mr-1"></i>Add Permissions
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Users with this Role -->
    <div class="mt-6">
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Users with this Role ({{ $role->users->count() }})</h3>
                @if ($role->users->count() > 0)
                    <a href="{{ route('users.index', ['role' => $role->id]) }}" class="text-sm text-primary-600 hover:text-primary-900">
                        View All Users
                    </a>
                @endif
            </div>
            
            @if ($role->users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Joined
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($role->users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                                                <span class="text-white font-medium text-xs">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if ($user->id === Auth::id())
                                            <div class="text-xs text-gray-500">You</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('users.show', $user) }}" class="text-primary-600 hover:text-primary-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-400 text-3xl mb-3"></i>
                    <p class="text-sm text-gray-500">No users assigned to this role</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection