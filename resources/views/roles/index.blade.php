@extends('layouts.app')

@section('title', 'Roles')
@section('breadcrumb', 'Roles')

@section('content')
<!-- Header with Create Button -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-900">Role Management</h2>
    <a href="{{ route('roles.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>Add New Role
    </a>
</div>

<!-- Roles Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Permissions
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Users
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($roles as $role)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                <div class="text-xs text-gray-500">{{ $role->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $role->description ?? 'No description' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($role->permissions->count() > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach ($role->permissions->take(3) as $permission)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $permission->name }}
                                </span>
                                @endforeach
                                @if ($role->permissions->count() > 3)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    +{{ $role->permissions->count() - 3 }} more
                                </span>
                                @endif
                            </div>
                        @else
                            <span class="text-sm text-gray-500">No permissions</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-900">{{ $role->users_count }}</span>
                            @if ($role->users_count > 0)
                            <a href="{{ route('roles.show', $role) }}" class="ml-2 text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-users text-xs"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('roles.show', $role) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if ($role->users_count == 0)
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @else
                        <button class="text-gray-400 cursor-not-allowed" title="Cannot delete role with assigned users">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                        No roles found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if ($roles->hasPages())
    <div class="mt-6">
        {{ $roles->links() }}
    </div>
    @endif
</div>
@endsection