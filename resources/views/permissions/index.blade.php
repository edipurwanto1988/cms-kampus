@extends('layouts.app')

@section('title', 'Permissions')
@section('breadcrumb', 'Permissions')

@section('content')
<!-- Header with Create Button -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-900">Permission Management</h2>
    <a href="{{ route('permissions.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>Add New Permission
    </a>
</div>

<!-- Permissions Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Permission
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Roles
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($permissions as $permission)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-green-600 flex items-center justify-center">
                                    <i class="fas fa-key text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                <div class="text-xs text-gray-500">{{ $permission->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $permission->description ?? 'No description' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($permission->roles->count() > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach ($permission->roles->take(3) as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                                @if ($permission->roles->count() > 3)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    +{{ $permission->roles->count() - 3 }} more
                                </span>
                                @endif
                            </div>
                        @else
                            <span class="text-sm text-gray-500">No roles</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('permissions.show', $permission) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('permissions.edit', $permission) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if ($permission->roles_count == 0)
                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this permission?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @else
                        <button class="text-gray-400 cursor-not-allowed" title="Cannot delete permission with assigned roles">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                        No permissions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if ($permissions->hasPages())
    <div class="mt-6">
        {{ $permissions->links() }}
    </div>
    @endif
</div>
@endsection