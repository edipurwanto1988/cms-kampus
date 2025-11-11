@extends('layouts.app')

@section('title', $lecturer->full_name)
@section('breadcrumb', $lecturer->full_name)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-shrink-0">
                @if($lecturer->photo)
                    <img class="w-32 h-32 rounded-full object-cover" src="{{ $lecturer->photo_url }}" alt="{{ $lecturer->full_name }}">
                @else
                    <div class="w-32 h-32 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-white text-4xl font-medium">{{ strtoupper(substr($lecturer->full_name ?? '?', 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $lecturer->full_name }}</h1>
                @if($lecturer->position_title)
                <p class="text-lg text-is-teal mb-4">{{ $lecturer->position_title }}</p>
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
                        <i class="fas fa-graduation-cap"></i>
                        <span class="text-sm">Google Scholar</span>
                    </a>
                    @endif
                    @if($lecturer->researchgate_url)
                    <a href="{{ $lecturer->researchgate_url }}" target="_blank" 
                       class="flex items-center gap-2 text-is-teal hover:text-is-teal/80 transition-colors">
                        <i class="fas fa-flask"></i>
                        <span class="text-sm">ResearchGate</span>
                    </a>
                    @endif
                    @if($lecturer->linkedin_url)
                    <a href="{{ $lecturer->linkedin_url }}" target="_blank" 
                       class="flex items-center gap-2 text-is-teal hover:text-is-teal/80 transition-colors">
                        <i class="fab fa-linkedin"></i>
                        <span class="text-sm">LinkedIn</span>
                    </a>
                    @endif
                </div>
                @endif
            </div>
            
            <!-- Status Badges -->
            <div class="flex flex-col gap-2">
                @if($lecturer->featured)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-star mr-1"></i> Featured
                </span>
                @endif
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    @if ($lecturer->is_active) bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    @if ($lecturer->is_active) Active @else Inactive @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Translations Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Available Translations</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($lecturer->translations as $translation)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                    {{ strtoupper($translation->locale) }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- Biography -->
    @if($lecturer->bio_html)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Biography</h2>
        <div class="prose max-w-none">
            {!! $lecturer->bio_html !!}
        </div>
    </div>
    @endif

    <!-- Research Interests -->
    @if($lecturer->research_interests)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Research Interests</h2>
        <div class="prose max-w-none">
            {!! $lecturer->research_interests !!}
        </div>
    </div>
    @endif

    <!-- Achievements -->
    @if($lecturer->achievement)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Achievements</h2>
        <div class="prose max-w-none">
            {!! $lecturer->achievement !!}
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('lecturers.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
        <a href="{{ route('lecturers.edit', $lecturer) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            <i class="fas fa-edit mr-2"></i>Edit Lecturer
        </a>
    </div>
</div>
@endsection