@extends('layouts.guest')

@section('title', 'Partners - Information Systems Department')

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap justify-between gap-3 mb-10 md:mb-12">
    <div class="flex min-w-72 flex-col gap-3">
        <p class="text-gray-900 text-4xl font-black leading-tight tracking-[-0.033em]">Our Partners</p>
        <p class="text-gray-600 text-base font-normal leading-normal">We collaborate with industry leaders and educational institutions to provide the best learning opportunities for our students.</p>
    </div>
</div>

<!-- Partners Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @forelse ($partners as $partner)
    <div class="bg-white rounded-xl border border-gray-200 p-6 text-center transition-all duration-300 hover:shadow-lg group">
        <!-- Partner Logo -->
        <div class="flex justify-center mb-6">
            @if($partner->logo)
                <img class="h-24 w-24 object-contain mx-auto" 
                     data-alt="{{ $partner->name }} logo" 
                     src="{{ $partner->logo_url ?? 'https://via.placeholder.com/300x300/004A79D/FFFFFF?text=' . strtoupper(substr($partner->name ?? '?', 0, 1)) }}">
            @else
                <div class="h-24 w-24 rounded-full bg-is-teal/20 flex items-center justify-center mx-auto">
                    <span class="text-4xl font-bold text-is-teal">{{ strtoupper(substr($partner->name ?? '?', 0, 1)) }}</span>
                </div>
            @endif
        </div>
        
        <!-- Partner Name -->
        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $partner->name }}</h3>
        
        <!-- Partner Description -->
        <p class="text-gray-600 text-sm leading-relaxed mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </p>
        
        <!-- Partner Link -->
        <a href="#" 
           class="inline-flex items-center text-is-teal hover:text-is-teal/80 font-medium transition-colors group-hover:text-is-teal/90">
            <span class="material-symbols-outlined text-lg">external_link</span>
            <span class="ml-2">Learn More</span>
        </a>
    </div>
    @empty
    <div class="col-span-full text-center py-16">
        <div class="text-gray-500">
            <span class="material-symbols-outlined text-6xl mb-4">handshake</span>
            <p class="text-lg font-medium mb-2">No Partners Available</p>
            <p class="text-sm">We're currently working on establishing partnerships with industry leaders. Check back soon for updates.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- CTA Section -->
<div class="bg-is-blue border-t text-white py-16 mt-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold mb-4">Interested in Partnering With Us?</h2>
        <p class="text-lg mb-8 text-blue-100">Join our network of educational and industry partners to create meaningful opportunities for students and faculty.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="mailto:partnerships@is-dept.edu" 
               class="inline-flex items-center justify-center rounded-lg border border-transparent bg-white text-is-blue px-8 py-3 text-base font-medium hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors">
                <span class="material-symbols-outlined text-xl mr-2">mail</span>
                Become a Partner
            </a>
        </div>
    </div>
</div>
@endsection