@extends('layouts.app')

@section('title', 'Lecturers')
@section('breadcrumb', 'Lecturers')

@section('content')
<div class="container mx-auto p-4">
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Lecturer Management</h1>
            <a href="{{ route('lecturers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New Lecturer
            </a>
        </div>
    </div>

    <!-- Lecturers Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lecturer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Position
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Expertise
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Translations
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($lecturers as $lecturer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($lecturer->photo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $lecturer->photo_url }}" alt="{{ $lecturer->full_name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                                                <span class="text-white font-medium">{{ strtoupper(substr($lecturer->full_name ?? '?', 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $lecturer->full_name }}</div>
                                        @if($lecturer->email)
                                        <div class="text-xs text-gray-500">{{ $lecturer->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $lecturer->position_title ?? 'Not specified' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $lecturer->expertise ?? 'Not specified' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    @if($lecturer->featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i> Featured
                                    </span>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if ($lecturer->is_active) bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if ($lecturer->is_active) Active @else Inactive @endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lecturer->translations_count }}</div>
                                @if ($lecturer->translations_count > 0)
                                    <div class="ml-2 flex -space-x-1">
                                        @foreach ($lecturer->translations->take(3) as $translation)
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium">
                                                {{ strtoupper(substr($translation->locale, 0, 2)) }}
                                            </span>
                                        @endforeach
                                        @if ($lecturer->translations_count > 3)
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-800 text-xs font-medium">
                                                +{{ $lecturer->translations_count - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('lecturers.show', $lecturer) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('lecturers.edit', $lecturer) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this lecturer? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No lecturers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($lecturers->hasPages())
            <div class="mt-6">
                {{ $lecturers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection