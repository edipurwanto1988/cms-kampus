@extends('layouts.guest')

@section('title', $post->translations->first()->title ?? $post->title . ' - Article')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm">
        <li>
            <a href="{{ route('guest.home.localized', app()->getLocale()) }}" class="text-gray-500 hover:text-gray-700">Home</a>
        </li>
        <li class="text-gray-400">/</li>
        <li>
            <a href="{{ route('guest.articles.localized', app()->getLocale()) }}" class="text-gray-500 hover:text-gray-700">Articles</a>
        </li>
        <li class="text-gray-400">/</li>
        <li class="text-gray-900">{{ Str::limit($post->translations->first()->title ?? $post->title, 50) }}</li>
    </ol>
</nav>

<!-- Article Content -->
<article class="flex flex-col gap-8">
    <div class="flex flex-col gap-4">
        <p class="text-is-teal text-sm font-bold uppercase tracking-wider">Article</p>
        <h1 class="text-is-charcoal text-4xl lg:text-5xl font-black leading-tight tracking-tight">
            {{ $post->translations->first()->title ?? $post->title }}
        </h1>
        <p class="text-slate-600 text-base font-normal">
            By {{ $post->author ?? 'Department Staff' }} | Published on {{ \Carbon\Carbon::parse($post->created_at)->format('F j, Y') }} | {{ Str::wordCount($post->translations->first()->content ?? $post->content) }} min read
        </p>
    </div>
    
    @if($post->featured_image)
    <div class="w-full bg-center bg-no-repeat bg-cover flex flex-col justify-end overflow-hidden rounded-xl min-h-[300px] lg:min-h-[400px]" 
         data-alt="{{ $post->translations->first()->title ?? $post->title }}" 
         style="background-image: url('{{ $post->featured_image }}')">
    </div>
    @endif
    
    <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
        <div class="md:w-3/4 space-y-6 text-is-charcoal font-body text-lg leading-relaxed">
            <div class="prose prose-lg max-w-none">
                {!! $post->translations->first()->content ?? $post->content !!}
            </div>
            
            @if($post->category)
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-2">Category</p>
                <a href="{{ route('guest.articles.localized', app()->getLocale()) }}?category={{ $post->category->id }}"
                   class="inline-flex items-center gap-2 bg-is-teal/10 text-is-teal px-3 py-1 rounded-lg text-sm font-medium hover:bg-is-teal/20 transition-colors">
                    <span class="material-symbols-outlined text-lg">folder</span>
                    {{ $post->category->translations->first()->name ?? $post->category->name }}
                </a>
            </div>
            @endif
        </div>
        
        <aside class="md:w-1/4">
            <div class="sticky top-24 space-y-6">
                <!-- Share Article -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Share Article</h3>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" 
                           class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-200 text-gray-600 hover:bg-primary hover:text-white transition-colors">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->translations->first()->title ?? $post->title) }}" target="_blank"
                           class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-200 text-gray-600 hover:bg-primary hover:text-white transition-colors">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                        <a href="{{ url()->current() }}" 
                           class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-200 text-gray-600 hover:bg-primary hover:text-white transition-colors"
                           onclick="navigator.clipboard.writeText('{{ url()->current() }}'); return false;">
                            <span class="material-symbols-outlined text-xl">link</span>
                        </a>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</article>

<!-- Related Articles -->
@if($relatedPosts->count() > 0)
<div class="border-t border-gray-200 my-12 pt-12">
    <h2 class="text-3xl font-bold text-is-charcoal mb-8">Related Articles</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($relatedPosts as $relatedPost)
        <div class="flex flex-col gap-4 group">
            @if($relatedPost->featured_image)
            <div class="overflow-hidden rounded-lg">
                <img class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" 
                     data-alt="{{ $relatedPost->translations->first()->title ?? $relatedPost->title }}" 
                     src="{{ $relatedPost->featured_image }}"/>
            </div>
            @endif
            <div>
                @if($relatedPost->category)
                <p class="text-xs text-is-teal font-semibold uppercase mb-2">
                    {{ $relatedPost->category->translations->first()->name ?? $relatedPost->category->name }}
                </p>
                @endif
                <h3 class="text-lg font-bold text-is-charcoal group-hover:text-is-teal transition-colors">
                    <a href="{{ route('guest.article.detail.localized', [app()->getLocale(), $relatedPost->slug]) }}">
                        {{ $relatedPost->translations->first()->title ?? $relatedPost->title }}
                    </a>
                </h3>
                <p class="text-sm text-slate-600 mt-1 line-clamp-2">
                    {{ Str::limit(strip_tags($relatedPost->translations->first()->content ?? $relatedPost->content), 120) }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection