@extends('layouts.guest')

@section('title', ($lecturer->translations->first()->full_name ?? $lecturer->full_name) . ' - Faculty Profile')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm">
        <li>
            <a href="{{ route('guest.home.localized', app()->getLocale()) }}" class="text-gray-500 hover:text-gray-700">Home</a>
        </li>
        <li class="text-gray-400">/</li>
        <li>
            <a href="{{ route('guest.lecturers.localized', app()->getLocale()) }}" class="text-gray-500 hover:text-gray-700">Faculty</a>
        </li>
        <li class="text-gray-400">/</li>
        <li class="text-gray-900">{{ $lecturer->translations->first()->full_name ?? $lecturer->full_name }}</li>
    </ol>
</nav>

<!-- Lecturer Profile Header -->
<div class="bg-white rounded-xl border border-gray-200 p-8 mb-8">
    <div class="flex flex-col md:flex-row gap-8">
        <div class="flex-shrink-0">
            <img class="w-48 h-48 rounded-full object-cover mx-auto md:mx-0" 
                 data-alt="Professional headshot of {{ $lecturer->translations->first()->full_name ?? $lecturer->full_name }}"
                 src="{{ $lecturer->photo_url }}"/>
        </div>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $lecturer->translations->first()->full_name ?? $lecturer->full_name }}</h1>
            @if($lecturer->translations->first()->position_title ?? $lecturer->position_title)
            <p class="text-xl text-is-teal mb-4">{{ $lecturer->translations->first()->position_title ?? $lecturer->position_title }}</p>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @if($lecturer->NUPTK)
                <div>
                    <span class="text-sm font-medium text-gray-500">NUPTK:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $lecturer->NUPTK }}</span>
                </div>
                @endif
                @if($lecturer->nip)
                <div>
                    <span class="text-sm font-medium text-gray-500">NIP:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $lecturer->nip }}</span>
                </div>
                @endif
                @if($lecturer->email)
                <div>
                    <span class="text-sm font-medium text-gray-500">Email:</span>
                    <a href="mailto:{{ $lecturer->email }}" class="text-sm text-is-teal hover:text-is-teal/80 ml-2">{{ $lecturer->email }}</a>
                </div>
                @endif
                @if($lecturer->phone)
                <div>
                    <span class="text-sm font-medium text-gray-500">Phone:</span>
                    <a href="tel:{{ $lecturer->phone }}" class="text-sm text-gray-900 ml-2">{{ $lecturer->phone }}</a>
                </div>
                @endif
                @if($lecturer->expertise)
                <div class="md:col-span-2">
                    <span class="text-sm font-medium text-gray-500">Expertise:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $lecturer->expertise }}</span>
                </div>
                @endif
            </div>
            
            <!-- Social Links -->
            @if($lecturer->scholar_url || $lecturer->researchgate_url || $lecturer->linkedin_url)
            <div class="flex gap-3">
                @if($lecturer->scholar_url)
                <a href="{{ $lecturer->scholar_url }}" target="_blank" 
                   class="flex items-center gap-2 text-is-teal hover:text-is-teal/80 transition-colors">
                    <span class="material-symbols-outlined">school</span>
                    <span class="text-sm">Google Scholar</span>
                </a>
                @endif
                @if($lecturer->researchgate_url)
                <a href="{{ $lecturer->researchgate_url }}" target="_blank" 
                   class="flex items-center gap-2 text-is-teal hover:text-is-teal/80 transition-colors">
                    <span class="material-symbols-outlined">science</span>
                    <span class="text-sm">ResearchGate</span>
                </a>
                @endif
                @if($lecturer->linkedin_url)
                <a href="{{ $lecturer->linkedin_url }}" target="_blank" 
                   class="flex items-center gap-2 text-is-teal hover:text-is-teal/80 transition-colors">
                    <span class="material-symbols-outlined">work</span>
                    <span class="text-sm">LinkedIn</span>
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Biography -->
@if($lecturer->bio_html)
<div class="bg-white rounded-xl border border-gray-200 p-8 mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Biography</h2>
    <div class="prose prose-lg max-w-none">
        {!! $lecturer->translations->first()->bio_html ?? $lecturer->bio_html !!}
    </div>
</div>
@endif

<!-- Research Interests -->
@if($lecturer->research_interests)
<div class="bg-white rounded-xl border border-gray-200 p-8 mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Research Interests</h2>
    <div class="prose prose-lg max-w-none">
        {!! $lecturer->translations->first()->research_interests ?? $lecturer->research_interests !!}
    </div>
</div>
@endif

<!-- Achievements -->
@if($lecturer->achievement)
<div class="bg-white rounded-xl border border-gray-200 p-8 mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Achievements</h2>
    <div class="prose prose-lg max-w-none">
        {!! $lecturer->translations->first()->achievement ?? $lecturer->achievement !!}
    </div>
</div>
@endif

<!-- Related Lecturers -->
@if($relatedLecturers->count() > 0)
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Faculty Members</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedLecturers as $relatedLecturer)
        <div class="bg-white rounded-xl border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow">
            <img class="w-24 h-24 rounded-full object-cover mx-auto mb-4"
                 data-alt="Professional headshot of {{ $relatedLecturer->translations->first()->full_name ?? $relatedLecturer->full_name }}"
                 src="{{ $relatedLecturer->photo_url }}"/>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $relatedLecturer->translations->first()->full_name ?? $relatedLecturer->full_name }}</h3>
            @if($relatedLecturer->translations->first()->position_title ?? $relatedLecturer->position_title)
            <p class="text-sm text-gray-600 mb-4">{{ $relatedLecturer->translations->first()->position_title ?? $relatedLecturer->position_title }}</p>
            @endif
            <a href="{{ route('guest.lecturer.detail.localized', [app()->getLocale(), $relatedLecturer->id]) }}"
               class="inline-block bg-is-teal/20 text-is-teal text-sm font-bold py-2 px-4 rounded-lg hover:bg-is-teal/30 transition-colors">
                View Profile
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Back to Faculty -->
<div class="text-center">
    <a href="{{ route('guest.lecturers.localized', app()->getLocale()) }}"
       class="inline-flex items-center gap-2 text-is-teal hover:text-is-teal/80 transition-colors">
        <span class="material-symbols-outlined">arrow_back</span>
        <span>Back to All Faculty</span>
    </a>
</div>
@endsection