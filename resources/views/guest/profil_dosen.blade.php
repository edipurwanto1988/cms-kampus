@extends('layouts.guest')

@section('title', 'Faculty & Staff - Information Systems Department')

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap justify-between gap-3 mb-10 md:mb-12">
    <div class="flex min-w-72 flex-col gap-3">
        <p class="text-gray-900 text-4xl font-black leading-tight tracking-[-0.033em]">Faculty & Staff</p>
        <p class="text-gray-600 text-base font-normal leading-normal">Meet our dedicated teaching staff from the Information Systems Department.</p>
    </div>
</div>

<!-- Lecturers Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    @forelse ($lecturers as $lecturer)
    <article class="flex flex-col sm:flex-row gap-6 bg-white p-6 rounded-xl border border-gray-200 transition-shadow hover:shadow-lg">
        <img class="w-32 h-32 rounded-full object-cover mx-auto sm:mx-0 flex-shrink-0" data-alt="Professional headshot of {{ $lecturer->translations->first()->full_name ?? $lecturer->full_name }}" src="{{ $lecturer->photo_url }}"/>
        <div class="flex flex-col w-full text-center sm:text-left">
            <h3 class="text-xl font-bold text-gray-900">{{ $lecturer->translations->first()->full_name ?? $lecturer->full_name }}</h3>
            <div class="text-sm text-gray-500 mt-2 space-y-1">
                @if($lecturer->translations->first()->position_title ?? $lecturer->position_title)
                <p><strong>Position:</strong> {{ $lecturer->translations->first()->position_title ?? $lecturer->position_title }}</p>
                @endif
                @if($lecturer->NUPTK)
                <p><strong>NUPTK:</strong> {{ $lecturer->NUPTK }}</p>
                @endif
                @if($lecturer->nip)
                <p><strong>NIP:</strong> {{ $lecturer->nip }}</p>
                @endif
                @if($lecturer->email)
                <p><strong>Email:</strong> {{ $lecturer->email }}</p>
                @endif
                @if($lecturer->translations->first()->expertise ?? $lecturer->expertise)
                <p><strong>Expertise:</strong> {{ $lecturer->translations->first()->expertise ?? $lecturer->expertise }}</p>
                @endif
            </div>
            <div class="flex gap-2 mt-4 justify-center sm:justify-start">
                @if($lecturer->scholar_url)
                <a href="{{ $lecturer->scholar_url }}" target="_blank" class="text-is-teal hover:text-is-teal/80 transition-colors">
                    <span class="material-symbols-outlined">school</span>
                </a>
                @endif
                @if($lecturer->researchgate_url)
                <a href="{{ $lecturer->researchgate_url }}" target="_blank" class="text-is-teal hover:text-is-teal/80 transition-colors">
                    <span class="material-symbols-outlined">science</span>
                </a>
                @endif
                @if($lecturer->linkedin_url)
                <a href="{{ $lecturer->linkedin_url }}" target="_blank" class="text-is-teal hover:text-is-teal/80 transition-colors">
                    <span class="material-symbols-outlined">work</span>
                </a>
                @endif
                
            </div>
        </div>
    </article>
    @empty
    <div class="col-span-full text-center py-16">
        <div class="text-gray-500">
            <span class="material-symbols-outlined text-6xl mb-4">person_search</span>
            <p class="text-lg">No faculty members available at the moment.</p>
            <p class="text-sm mt-2">Please check back later for updates.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($lecturers->hasPages())
<div class="mt-12">
    {{ $lecturers->links() }}
</div>
@endif
@endsection