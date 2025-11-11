@extends('layouts.app')

@section('title', 'Languages')
@section('breadcrumb', 'Languages')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Languages</h1>
            <a href="{{ route('languages.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                Add New Language
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white  rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 ">
                <thead class="bg-gray-50 ">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500  uppercase tracking-wider">
                            Code
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500  uppercase tracking-wider">
                            Name
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500  uppercase tracking-wider">
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500  uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white  divide-y divide-gray-200 ">
                    @forelse($languages as $language)
                        <tr class="hover:bg-gray-50 ">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                                {{ $language->code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 ">
                                {{ $language->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($language->is_default)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ">
                                        Default
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800  ">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if(!$language->is_default)
                                    <form action="{{ route('languages.setDefault', $language) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        <button type="submit"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            title="Set as Default">
                                            Set Default
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('languages.edit', $language) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Edit
                                </a>

                                @if(!$language->is_default)
                                    <form action="{{ route('languages.destroy', $language) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this language?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900  ">
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 ">Delete</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 ">
                                No languages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection