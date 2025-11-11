@extends('layouts.guest')

@section('title', 'Server Error')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-red-600 mb-4">500</h1>
            <h2 class="text-2xl font-semibold text-gray-600 mb-4">Server Error</h2>
            <p class="text-gray-500 mb-8">Something went wrong on our end. Please try again later.</p>
            <a href="{{ url('/') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                Go to Homepage
            </a>
        </div>
    </div>
</div>
@endsection