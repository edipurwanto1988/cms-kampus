@extends('layouts.guest')


@section('title', guestSetting($seoSettings, 'site_meta_title', 'Information Systems Department'))

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden rounded-xl">
    <div class="absolute inset-0 bg-cover bg-center" data-alt="Modern university campus building with students walking by" style='background-image: url("{{ getSettingValue($landingSettings, 'hero_image_url', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAbd74FgEzGI6MQf5glsKr9NrXFsCY4DymJupKsYDddbRw4WOfmaw2zVvyyVu4BE4rKHFT4OiQVyAjRf76JWIsvlf_LTaSMWrGSeRgtDU5PtSjL9FpkjW6VuSo-LCOGRTsC3Yny9ZMkHKZtuqKO35BIYj-uufUUF9RtJnNr11laooMeThVtgm5rYVSQ4qWwKPuOEMcskF-M0PE0s9czr8Z3wt45WQTySpHCX-Eo5gvmrqYFScmnByq7S88iv8yq9kNfjYRLZ3STJw') }}");'></div>
    <div class="absolute inset-0 bg-gradient-to-t from-is-blue/80 via-is-blue/40 to-transparent"></div>
    <div class="relative flex min-h-[60vh] md:min-h-[520px] flex-col gap-6 items-center justify-center text-center p-6 md:p-10">
        <div class="flex flex-col gap-2 max-w-3xl">
            <h1 class="text-white text-4xl font-black leading-tight tracking-[-0.033em] md:text-6xl">{{ getSettingValue($landingSettings, 'hero_title', 'Welcome to the Forefront of Information Systems') }}</h1>
            <h2 class="text-slate-100 text-base font-normal leading-normal md:text-lg">{{ getSettingValue($landingSettings, 'hero_subtitle', 'Shaping the future of technology, business, and innovation. Discover your potential with us.') }}</h2>
        </div>
        <div class="flex-wrap gap-3 flex mt-4">
            <a href="{{ getSettingValue($landingSettings, 'hero_button1_url', route('guest.articles.localized', app()->getLocale())) }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 md:h-12 md:px-5 bg-is-teal hover:bg-is-teal/90 text-white text-sm font-bold leading-normal tracking-[0.015em] md:text-base transition-transform duration-200 hover:scale-105">
                <span class="truncate">{{ getSettingValue($landingSettings, 'hero_button1_text', 'Explore Our Programs') }}</span>
            </a>
            <a href="{{ getSettingValue($landingSettings, 'hero_button2_url', route('guest.contact.localized', app()->getLocale())) }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 md:h-12 md:px-5 bg-background-light/90 hover:bg-background-light/100 text-is-charcoal text-sm font-bold leading-normal tracking-[0.015em] md:text-base transition-transform duration-200 hover:scale-105">
                <span class="truncate">{{ getSettingValue($landingSettings, 'hero_button2_text', 'Contact Us') }}</span>
            </a>
        </div>
    </div>
</section>

<!-- Head of Department Message -->
<section class="py-10 md:py-16">
    <div class="p-4">
        <div class="flex flex-col md:flex-row items-stretch justify-between gap-8 rounded-xl bg-white p-6 md:p-8 shadow-[0_0_15px_rgba(0,0,0,0.07)]">
            <div class="flex flex-[2_2_0px] flex-col gap-4 justify-center">
                <div class="flex flex-col gap-2">
                    <p class="text-is-teal text-sm font-medium leading-normal uppercase tracking-wider">A Message From Our Head of Department</p>
                    <p class="text-is-charcoal text-2xl font-bold leading-tight">{{ getSettingValue($landingSettings, 'hod_name', 'Dr. Eleanor Vance') }}</p>
                    <p class="text-slate-600 text-base font-normal leading-relaxed">{{ getSettingValue($landingSettings, 'hod_message', 'Our mission is to cultivate the next generation of leaders in the digital world. We provide a dynamic learning environment where theory meets practice, empowering our students to solve complex problems and drive innovation.') }}</p>
                </div>
                <a href="{{ getSettingValue($landingSettings, 'hod_read_more_url', route('guest.lecturers.localized', app()->getLocale())) }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 flex-row-reverse bg-is-light-gray text-is-charcoal hover:bg-slate-200 text-sm font-medium leading-normal w-fit gap-2 transition-transform duration-200 hover:scale-105">
                    <span class="truncate">Read More</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
            <div class="w-full md:w-1/3 aspect-[4/5] bg-center bg-no-repeat bg-cover rounded-xl flex-1" data-alt="Professional headshot of {{ getSettingValue($landingSettings, 'hod_name', 'Dr. Eleanor Vance') }}, Head of Department" style='background-image: url("{{ getSettingValue($landingSettings, 'hod_photo_url', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDg-CyWHDXlx436hV0Z0KwGO_v4eZDc8HvyMECL4GoKqFvC-vNr3OLOw3Ab0fSrRvcwhYs5Byzskp-NyheLSGfPd5N6Om2vgdzyenZyMFhi6VclQye7Q0HHZrpdsdJUNFdSioH8ngH-B8O42BGLSceKDZaWI4r_VdXpTWjgLYWjuOygKwAu3E-N5eYOofiee7-0cOGyDHUCEW0gBCf9SKhthNGI_jUI7IehmAKAt2BAvRZcLO-J9_PAoidQzte041T1-PdR7Jgr4A') }}");'></div>
        </div>
    </div>
</section>

<!-- Announcements and Articles -->
<section class="py-10 md:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Announcements -->
        <div class="flex flex-col">
            <h2 class="text-is-charcoal text-3xl font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">{{ getSettingValue($landingSettings, 'announcements_title', 'Pengumuman') }}</h2>
            <div class="flex flex-col gap-6 mt-6">
                @forelse ($latestPosts->take(3) as $post)
                <a href="{{ route('guest.article.detail.localized', [app()->getLocale(), $post->slug]) }}" class="flex items-start gap-4 p-4 rounded-lg hover:bg-is-light-gray transition-colors duration-200 cursor-pointer">
                    <div class="flex flex-col items-center justify-center bg-is-teal text-white rounded-lg p-3 w-16 h-16 shrink-0">
                        <span class="text-2xl font-bold">{{ \Carbon\Carbon::parse($post->created_at)->format('d') }}</span>
                        <span class="text-xs uppercase font-medium">{{ \Carbon\Carbon::parse($post->created_at)->format('M') }}</span>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="font-bold text-lg text-is-charcoal">{{ $post->translations->first()->title ?? $post->title }}</h3>
                        <p class="text-sm text-slate-600 mt-1">{{ Str::limit(strip_tags($post->translations->first()->content ?? $post->content), 100) }}</p>
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-slate-500">
                    <p>No announcements available at the moment.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-8 px-4">
                <a href="{{ route('guest.articles.localized', app()->getLocale()) }}" class="flex w-full min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-is-light-gray text-is-charcoal hover:bg-slate-200 text-sm font-medium leading-normal gap-2 transition-transform duration-200 hover:scale-[1.02]">
                    <span class="truncate">{{ getSettingValue($landingSettings, 'announcements_title', 'Pengumuman') }} Lainnya</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
        </div>
        
        <!-- Articles -->
        <div class="flex flex-col">
            <h2 class="text-is-charcoal text-3xl font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">{{ getSettingValue($landingSettings, 'articles_title', 'Artikel') }}</h2>
            <div class="flex flex-col gap-6 mt-6">
                @forelse ($latestPosts->take(3) as $post)
                <a href="{{ route('guest.article.detail.localized', [app()->getLocale(), $post->slug]) }}" class="flex items-start gap-4 p-4 rounded-lg hover:bg-is-light-gray transition-colors duration-200 cursor-pointer">
                    <div class="w-28 h-20 bg-cover bg-center rounded-lg shrink-0" style="background-image: url('{{ $post->featured_image ?? 'https://via.placeholder.com/150x100/004A99/FFFFFF?text=' . urlencode(substr($post->title, 0, 10)) }}')"></div>
                    <div class="flex flex-col">
                        <p class="text-xs text-is-teal font-semibold uppercase">{{ $post->category->translations->first()->name ?? $post->category->name ?? 'General' }}</p>
                        <h3 class="font-bold text-lg text-is-charcoal leading-tight mt-1">{{ $post->translations->first()->title ?? $post->title }}</h3>
                        <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ Str::limit(strip_tags($post->translations->first()->content ?? $post->content), 80) }}</p>
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-slate-500">
                    <p>No articles available at the moment.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-8 px-4">
                <a href="{{ route('guest.articles.localized', app()->getLocale()) }}" class="flex w-full min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-is-light-gray text-is-charcoal hover:bg-slate-200 text-sm font-medium leading-normal gap-2 transition-transform duration-200 hover:scale-[1.02]">
                    <span class="truncate">{{ getSettingValue($landingSettings, 'articles_title', 'Artikel') }} Lainnya</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Lecturers -->
@if($featuredLecturers->count() > 0)
<section class="py-10 md:py-16">
    <div class="px-4 text-center">
        <h2 class="text-is-charcoal text-3xl font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">{{ getSettingValue($landingSettings, 'faculty_title', 'Meet Our Faculty') }}</h2>
        <p class="text-slate-600 max-w-2xl mx-auto">{{ getSettingValue($landingSettings, 'faculty_subtitle', 'Learn from experienced educators and industry experts dedicated to your success.') }}</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-12 px-4">
        @foreach($featuredLecturers as $lecturer)
        <div class="flex flex-col items-center gap-4 text-center group">
            <div class="w-32 h-32 rounded-full bg-cover bg-center border-4 border-is-teal/20 group-hover:border-is-teal/50 transition-all duration-300" style="background-image: url('{{ $lecturer->photo_url }}')"></div>
            <div>
                <h3 class="text-xl font-bold text-is-charcoal">{{ $lecturer->translations->first()->full_name ?? $lecturer->full_name }}</h3>
                <p class="text-sm text-slate-600">{{ $lecturer->translations->first()->position_title ?? $lecturer->position_title }}</p>
            </div>
            <a href="{{ route('guest.lecturer.detail.localized', [app()->getLocale(), $lecturer->id]) }}" class="text-is-teal hover:text-is-teal/80 text-sm font-medium transition-colors">View Profile</a>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('guest.lecturers.localized', app()->getLocale()) }}" class="inline-flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-is-teal hover:bg-is-teal/90 text-white text-sm font-bold leading-normal tracking-[0.015em] transition-transform duration-200 hover:scale-105">
            <span class="truncate">View All Faculty</span>
        </a>
    </div>
</section>
@endif

<!-- Partners -->
@if($partners->count() > 0)
<section id="partners" class="py-10 md:py-16 bg-is-light-gray rounded-xl">
    <div class="px-4 text-center">
        <h2 class="text-is-charcoal text-3xl font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">{{ getSettingValue($landingSettings, 'partners_title', 'Our Industry Partners') }}</h2>
        <p class="text-slate-600 max-w-2xl mx-auto">{{ getSettingValue($landingSettings, 'partners_subtitle', 'We collaborate with leading organizations to provide our students with real-world experience and career opportunities.') }}</p>
    </div>
    <div class="mt-12 px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-8 gap-y-12 md:gap-x-12 items-center justify-items-center">
            @foreach($partners as $partner)
            <div class="flex flex-col items-center gap-3">
                <img class="max-h-16 w-auto grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300" data-alt="{{ $partner->name }}" src="{{ $partner->logo_url ?? 'https://via.placeholder.com/150x80/cccccc/666666?text=Partner' }}"/>
                <p class="text-sm font-medium text-slate-600">{{ $partner->name }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection