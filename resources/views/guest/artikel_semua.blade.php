@extends('layouts.guest')

@section('title', 'Articles & Publications - Information Systems Department')

@section('content')
<!-- Hero Section -->
<section class="mb-12">
    <div class="flex flex-col md:flex-row gap-8 items-center bg-white p-8 rounded-xl border border-gray-200">
        <div class="flex flex-col gap-4 text-left w-full md:w-1/2">
            <h1 class="text-4xl md:text-5xl font-black leading-tight tracking-tighter text-gray-900">
                Welcome to the Information Systems Department
            </h1>
            <p class="text-base md:text-lg text-gray-600">
                Exploring the intersection of technology, data, and business to shape the future.
            </p>
            <a href="{{ route('guest.contact.localized', app()->getLocale()) }}" class="flex w-fit min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold tracking-wide hover:bg-primary/90 transition-colors mt-4">
                <span class="truncate">Explore Our Latest Research</span>
            </a>
        </div>
        <div class="w-full md:w-1/2">
            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg" data-alt="Abstract image representing technology and data networks" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBiWsYVVJv2BlkkBpDSp4OpERIIdh7XX1VCNHPhdr6_X4jHooSU5Uurp_LfVpGlDKOj8w3mHdTXdp_MbRwRYIP_sF8Y_U5IvnY3oJv6d0sQDfSfldL6ojbRnJ89UTV44lU3GuDqaQjpjw2ll9uj3TlXQOizWqaW-5u5NsRij1-jjtGiO0koXRVz8rzSKlRHNwgbsp16GXa4NXqYK2CBlijOqRNdxAHD-m_q8vmowE_wBeAjIMdY5Z3P-aSgaK8VNWw-JbN-FZkIDw");'></div>
        </div>
    </div>
</section>

<!-- Main Content Area -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Articles List -->
    <div class="lg:col-span-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <h2 class="text-gray-900 text-2xl font-bold tracking-tight">Latest Articles & Publications</h2>
            <div class="relative mt-4 sm:mt-0">
                <select class="appearance-none w-full sm:w-auto bg-white border border-gray-300 rounded-lg py-2 pl-4 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                    <option>Sort by: Newest First</option>
                    <option>Sort by: Oldest First</option>
                </select>
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">expand_more</span>
            </div>
        </div>
        
        <!-- Article Cards -->
        <div class="space-y-6">
            @forelse ($posts as $post)
            <article class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <a href="{{ route('guest.article.detail.localized', [app()->getLocale(), $post->slug]) }}" class="block">
                    @if($post->featured_image)
                    <div class="w-full h-48 bg-cover bg-center rounded-lg mb-4" style="background-image: url('{{ $post->featured_image }}')"></div>
                    @endif
                    <h3 class="text-xl font-bold text-primary hover:underline">{{ $post->translations->first()->title ?? $post->title }}</h3>
                    <p class="text-sm text-gray-500 mt-2">By {{ $post->author ?? 'Admin' }} | Published on {{ $post->created_at->format('F j, Y') }}</p>
                    <p class="text-gray-600 mt-3">{{ Str::limit(strip_tags($post->translations->first()->content ?? $post->content), 150) }}</p>
                </a>
            </article>
            @empty
            <div class="text-center py-16">
                <div class="text-gray-500">
                    <span class="material-symbols-outlined text-6xl mb-4">article</span>
                    <p class="text-lg">No articles available at the moment.</p>
                    <p class="text-sm mt-2">Please check back later for new content.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($posts->hasPages())
        <nav aria-label="Pagination" class="flex items-center justify-center mt-10">
            {{ $posts->links() }}
        </nav>
        @endif
    </div>
    
    <!-- Sidebar for Filtering -->
    <aside class="lg:col-span-4">
        <div class="sticky top-24 space-y-8">
            <!-- Search Bar -->
            <div>
                <label class="text-gray-900 text-lg font-bold" for="search-articles">Search Articles</label>
                <form action="{{ route('guest.search.localized', app()->getLocale()) }}" method="GET" class="relative mt-2">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="material-symbols-outlined text-gray-500">search</span>
                    </div>
                    <input class="w-full rounded-lg border-gray-300 bg-white pl-10 pr-4 py-2 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary/50" id="search-articles" name="q" placeholder="Search by keywords..." type="search" value="{{ request('q') }}"/>
                </form>
            </div>
            
            <!-- Filter by Category -->
            <div>
                <h3 class="text-gray-900 text-lg font-bold">Filter by Category</h3>
                <div class="flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('guest.articles.localized', app()->getLocale()) }}" class="flex h-8 items-center justify-center rounded-full px-4 bg-accent text-white text-sm font-medium {{ !request('category') ? 'bg-accent' : 'bg-secondary text-gray-700 hover:bg-accent/20' }}">All</a>
                    @foreach($categories as $category)
                    <a href="{{ route('guest.articles.localized', [app()->getLocale(), 'category' => $category->slug]) }}" class="flex h-8 items-center justify-center rounded-full px-4 {{ request('category') == $category->slug ? 'bg-accent text-white' : 'bg-secondary text-gray-700 hover:bg-accent/20' }} text-sm font-medium">{{ $category->translations->first()->name ?? $category->name }}</a>
                    @endforeach
                </div>
            </div>
            
            <!-- Recent Posts -->
            @if($posts->count() > 0)
            <div>
                <h3 class="text-gray-900 text-lg font-bold">Recent Posts</h3>
                <div class="space-y-3 mt-4">
                    @foreach($posts->take(5) as $recentPost)
                    <a href="{{ route('guest.article.detail.localized', [app()->getLocale(), $recentPost->slug]) }}" class="block p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <h4 class="font-medium text-gray-900 text-sm line-clamp-2">{{ $recentPost->translations->first()->title ?? $recentPost->title }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $recentPost->created_at->format('M j, Y') }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </aside>
</div>
@endsection