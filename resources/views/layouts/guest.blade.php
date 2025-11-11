@php
    // Use settings passed from controller if available, otherwise load them
    $generalSettings = $generalSettings ?? App\Models\Setting::with(['values' => function ($query) {
        $query->where(function($q) {
            $q->where('locale', app()->getLocale())
              ->orWhereNull('locale');
        })->latest();
    }])->where('group_name', 'general')->get()->keyBy('key_name');
    
    $seoSettings = $seoSettings ?? App\Models\Setting::with(['values' => function ($query) {
        $query->where(function($q) {
            $q->where('locale', app()->getLocale())
              ->orWhereNull('locale');
        })->latest();
    }])->where('group_name', 'seo')->get()->keyBy('key_name');
    
    $socialSettings = $socialSettings ?? App\Models\Setting::with(['values' => function ($query) {
        $query->where(function($q) {
            $q->where('locale', app()->getLocale())
              ->orWhereNull('locale');
        })->latest();
    }])->where('group_name', 'social')->get()->keyBy('key_name');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', getGuestSettingValue($seoSettings, 'site_meta_title', 'Information Systems Department'))</title>
    
    @if(getGuestSettingValue($seoSettings, 'site_meta_description'))
    <meta name="description" content="{{ getGuestSettingValue($seoSettings, 'site_meta_description') }}">
    @endif
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Lora:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "secondary": "#F0F2F5",
                        "accent": "#00A79D",
                        "is-blue": "#003366",
                        "is-teal": "#00A79D",
                        "is-gold": "#FFC72C",
                        "is-charcoal": "#333333",
                        "is-light-gray": "#F4F4F4",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"],
                        "body": ["Lora", "serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    keyframes: {
                        'scroll-infinite': {
                            '0%': { transform: 'translateX(0)' },
                            '100%': { transform: 'translateX(-50%)' },
                        }
                    },
                    animation: {
                        'scroll-infinite': 'scroll-infinite 40s linear infinite',
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light font-display text-is-charcoal">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<!-- Header -->
<header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200/80 bg-background-light/80 px-4 sm:px-8 md:px-16 lg:px-24 xl:px-40 py-3 backdrop-blur-sm">
    <div class="flex items-center gap-4">
        <div class="text-is-blue size-7">
            <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z" fill="currentColor" fill-rule="evenodd"></path>
            </svg>
        </div>
        <h2 class="text-is-blue text-lg font-bold leading-tight tracking-[-0.015em]">{{ getGuestSettingValue($generalSettings, 'site_name', 'Information Systems') }}</h2>
    </div>
    <div class="hidden lg:flex flex-1 justify-end items-center gap-8">
        <div class="flex items-center gap-9">
            <a class="text-is-charcoal text-sm font-medium leading-normal hover:text-is-teal transition-colors {{ request()->routeIs('guest.home*') ? 'text-is-teal' : '' }}" href="{{ route('guest.home.localized', app()->getLocale()) }}">{{ uiTrans('nav_home', 'Home') }}</a>
            <a class="text-is-charcoal text-sm font-medium leading-normal hover:text-is-teal transition-colors {{ request()->routeIs('guest.articles*') ? 'text-is-teal' : '' }}" href="{{ route('guest.articles.localized', app()->getLocale()) }}">{{ uiTrans('nav_articles', 'Articles') }}</a>
            <a class="text-is-charcoal text-sm font-medium leading-normal hover:text-is-teal transition-colors {{ request()->routeIs('guest.lecturers*') ? 'text-is-teal' : '' }}" href="{{ route('guest.lecturers.localized', app()->getLocale()) }}">{{ uiTrans('nav_faculty', 'Faculty') }}</a>
            
            <a class="text-is-charcoal text-sm font-medium leading-normal hover:text-is-teal transition-colors {{ request()->routeIs('guest.contact*') ? 'text-is-teal' : '' }}" href="{{ route('guest.contact.localized', app()->getLocale()) }}">{{ uiTrans('nav_contact', 'Contact') }}</a>
        </div>
        <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-is-teal hover:bg-is-teal/90 text-white text-sm font-bold leading-normal tracking-[0.015em] transition-transform duration-200 hover:scale-105">
            <span class="truncate">{{ uiTrans('btn_apply_now', 'Apply Now') }}</span>
        </button>
        
        {{-- Language Switcher - Only show on guest routes, not admin routes --}}
        @php
            $currentLocale = app()->getLocale();
            $availableLanguages = \App\Models\Language::getAllOrdered();
            $currentRoute = request()->route()->getName();
            $isAdminRoute = str_starts_with($currentRoute, 'dashboard') ||
                           str_starts_with($currentRoute, 'users') ||
                           str_starts_with($currentRoute, 'roles') ||
                           str_starts_with($currentRoute, 'permissions') ||
                           str_starts_with($currentRoute, 'pages') ||
                           str_starts_with($currentRoute, 'posts') ||
                           str_starts_with($currentRoute, 'categories') ||
                           str_starts_with($currentRoute, 'partners') ||
                           str_starts_with($currentRoute, 'lecturers') ||
                           str_starts_with($currentRoute, 'languages') ||
                           str_starts_with($currentRoute, 'settings') ||
                           str_starts_with($currentRoute, 'menus') ||
                           $currentRoute === 'cms.index' ||
                           $currentRoute === 'cms.update';
        @endphp
        
        @if(!$isAdminRoute)
        <div class="relative group">
            <button class="flex items-center gap-2 text-is-charcoal text-sm font-medium leading-normal hover:text-is-teal transition-colors">
                @foreach($availableLanguages as $lang)
                    @if($lang->code == $currentLocale)
                        @if($lang->code == 'en')
                            <img alt="English Flag" class="w-6 h-auto rounded-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFkVr2berLFLGl7uqwBo_NAqPwO4gcTtZWtCsnabo8yzif5slr_oZCWOb14KJRi2pzUKenqeBjm_BI__Qw3m2TeofQ1IHWHCTdG0AeCmKHXAIDQC2XAxutgb-v0FYpQrCWhznyjRrUPHxXlysy1-9VtmNOSbaNBpmKduqMFJuK0yLzZSmqPt2ppuT510BsVVv7ESS7nZxevdWT75s9kXkqvdlqfhezdCWdgO1RMpRwT_lXuyEKY4O50cjTQ6Q-co2AGNpy5dM9tw"/>
                        @elseif($lang->code == 'id')
                            <img alt="Indonesia Flag" class="w-6 h-auto rounded-full object-cover" src="https://upload.wikimedia.org/wikipedia/commons/9/9f/Flag_of_Indonesia.svg"/>
                        @elseif($lang->code == 'arab')
                            <img alt="Arabic Flag" class="w-6 h-auto rounded-full object-cover" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Flag_of_Saudi_Arabia.svg/320px-Flag_of_Saudi_Arabia.svg.png"/>
                        @else
                            <span class="text-xs font-bold">{{ strtoupper($lang->code) }}</span>
                        @endif
                        <span class="text-xs">{{ $lang->name }}</span>
                    @endif
                @endforeach
                <span class="material-symbols-outlined text-xl transition-transform duration-300 group-hover:rotate-180">expand_more</span>
            </button>
            <div class="absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform scale-95 group-hover:scale-100 origin-top-right z-50">
                @foreach($availableLanguages as $lang)
                    <a href="{{ route('language.switch', $lang->code) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-is-charcoal hover:bg-is-light-gray transition-colors {{ $lang->code == $currentLocale ? 'bg-is-light-gray font-semibold' : '' }}">
                        @if($lang->code == 'en')
                            <img alt="English Flag" class="w-5 h-auto" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFkVr2berLFLGl7uqwBo_NAqPwO4gcTtZWtCsnabo8yzif5slr_oZCWOb14KJRi2pzUKenqeBjm_BI__Qw3m2TeofQ1IHWHCTdG0AeCmKHXAIDQC2XAxutgb-v0FYpQrCWhznyjRrUPHxXlysy1-9VtmNOSbaNBpmKduqMFJuK0yLzZSmqPt2ppuT510BsVVv7ESS7nZxevdWT75s9kXkqvdlqfhezdCWdgO1RMpRwT_lXuyEKY4O50cjTQ6Q-co2AGNpy5dM9tw"/>
                        @elseif($lang->code == 'id')
                            <img alt="Indonesia Flag" class="w-5 h-auto" src="https://upload.wikimedia.org/wikipedia/commons/9/9f/Flag_of_Indonesia.svg"/>
                        @elseif($lang->code == 'arab')
                            <img alt="Arabic Flag" class="w-5 h-auto" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Flag_of_Saudi_Arabia.svg/320px-Flag_of_Saudi_Arabia.svg.png"/>
                        @else
                            <span class="text-xs font-bold">{{ strtoupper($lang->code) }}</span>
                        @endif
                        <span>{{ $lang->name }}</span>
                        @if($lang->is_default)
                            <span class="text-xs text-is-teal font-medium">{{ uiTrans('label_default', 'Default') }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    <button class="lg:hidden text-is-charcoal">
        <span class="material-symbols-outlined text-3xl">menu</span>
    </button>
</header>

<!-- Main Content -->
<main class="flex flex-1 justify-center py-5">
    <div class="layout-content-container flex flex-col w-full max-w-6xl flex-1 px-4 sm:px-6 md:px-8">
        @yield('content')
    </div>
</main>

<!-- Footer -->
<footer class="bg-is-blue border-t text-white mt-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-bold">{{ getGuestSettingValue($generalSettings, 'site_name', 'Information Systems') }}</h3>
                <p class="text-sm text-slate-300 mt-2">{{ uiTrans('footer_address', '123 University Drive<br/>Innovate City, ST 12345') }}</p>
                <p class="text-sm text-slate-300 mt-2">
                    <a class="hover:text-is-teal transition-colors" href="tel:123-456-7890">(123) 456-7890</a><br/>
                    <a class="hover:text-is-teal transition-colors" href="mailto:is.department@university.edu">is.department@university.edu</a>
                </p>
            </div>
            <div>
                <h4 class="font-semibold tracking-wide uppercase">{{ uiTrans('footer_quick_links', 'Quick Links') }}</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a class="text-slate-300 hover:text-white transition-colors" href="{{ route('guest.home.localized', app()->getLocale()) }}">{{ uiTrans('nav_home', 'Home') }}</a></li>
                    <li><a class="text-slate-300 hover:text-white transition-colors" href="{{ route('guest.articles.localized', app()->getLocale()) }}">{{ uiTrans('nav_articles', 'Articles') }}</a></li>
                    <li><a class="text-slate-300 hover:text-white transition-colors" href="{{ route('guest.lecturers.localized', app()->getLocale()) }}">{{ uiTrans('nav_faculty', 'Faculty') }}</a></li>
                    <li><a class="text-slate-300 hover:text-white transition-colors" href="{{ route('guest.contact.localized', app()->getLocale()) }}">{{ uiTrans('nav_contact', 'Contact') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold tracking-wide uppercase">{{ uiTrans('footer_resources', 'Resources') }}</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a class="text-slate-300 hover:text-white transition-colors" href="#">{{ uiTrans('footer_student_handbook', 'Student Handbook') }}</a></li>
                    <li><a class="text-slate-300 hover:text-white transition-colors" href="#">{{ uiTrans('footer_career_services', 'Career Services') }}</a></li>
                    <li><a class="text-slate-300 hover:text-white transition-colors" href="#">{{ uiTrans('footer_university_policies', 'University Policies') }}</a></li>
                    <li><a class="text-slate-300 hover:text-white transition-colors" href="{{ route('guest.contact.localized', app()->getLocale()) }}">{{ uiTrans('nav_contact', 'Contact') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold tracking-wide uppercase">{{ uiTrans('footer_connect', 'Connect With Us') }}</h4>
                <div class="flex mt-4 space-x-4">
                    @if(getGuestSettingValue($socialSettings, 'facebook_url'))
                    <a class="text-slate-300 hover:text-white transition-transform duration-200 hover:scale-110" href="{{ getGuestSettingValue($socialSettings, 'facebook_url') }}" target="_blank">
                        <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                        </svg>
                    </a>
                    @endif
                    
                    @if(getGuestSettingValue($socialSettings, 'instagram_url'))
                    <a class="text-slate-300 hover:text-white transition-transform duration-200 hover:scale-110" href="{{ getGuestSettingValue($socialSettings, 'instagram_url') }}" target="_blank">
                        <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.059-1.281.073-1.689.073-4.948s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98C15.667.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z"></path>
                        </svg>
                    </a>
                    @endif
                    
                    @if(getGuestSettingValue($socialSettings, 'twitter_url'))
                    <a class="text-slate-300 hover:text-white transition-transform duration-200 hover:scale-110" href="{{ getGuestSettingValue($socialSettings, 'twitter_url') }}" target="_blank">
                        <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-2.43.05-4.86-.95-6.69-2.81-1.77-1.8-2.59-4.28-2.4-6.64.19-2.44 1.69-4.63 3.73-5.96 2.05-1.34 4.61-1.65 6.93-1.33.22.02.43.03.65.05v-5.18c-.9-.01-1.79-.09-2.69-.12-1.2-.04-2.39-.1-3.59-.23-1.16-.12-2.26-.38-3.32-.75C2.14 14.12.75 12.19.75 9.71c0-1.56.52-3.05 1.52-4.25C4.21 2.72 6.6 1.48 9.2.85c1.1-.25 2.25-.33 3.32-.33z"></path>
                        </svg>
                    </a>
                    @endif
                    
                    @if(getGuestSettingValue($socialSettings, 'youtube_url'))
                    <a class="text-slate-300 hover:text-white transition-transform duration-200 hover:scale-110" href="{{ getGuestSettingValue($socialSettings, 'youtube_url') }}" target="_blank">
                        <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path clip-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" fill-rule="evenodd"></path>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-8 border-t border-slate-700 pt-8 text-center text-sm text-slate-400">
            <p>© {{ date('Y') }} {{ getGuestSettingValue($generalSettings, 'site_name', 'Information Systems Department') }}. {{ uiTrans('footer_all_rights_reserved', 'All Rights Reserved') }}.</p>
        </div>
    </div>
</footer>
</div>
</div>

@stack('scripts')
</body>
</html>