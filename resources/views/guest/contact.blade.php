@extends('layouts.guest')

@section('title', uiTrans('contact_title', 'Contact Us') . ' - ' . guestSetting($generalSettings, 'site_name', 'Information Systems Department'))

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap justify-between gap-3 mb-10 md:mb-12">
    <div class="flex min-w-72 flex-col gap-3">
        <p class="text-gray-900 text-4xl font-black leading-tight tracking-[-0.033em]">{{ uiTrans('contact_title', 'Contact Us') }}</p>
        <p class="text-gray-600 text-base font-normal leading-normal">{{ uiTrans('contact_subtitle', 'Get in touch with our Information Systems Department. We\'re here to help and answer any questions you may have.') }}</p>
    </div>
</div>

<!-- Contact Information Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 mb-12">
    <!-- Contact Form -->
    <div class="bg-white rounded-xl border border-gray-200 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ uiTrans('contact_send_message', 'Send us a Message') }}</h2>
        
        <form class="space-y-6" method="POST" action="{{ route('guest.contact.submit.localized', app()->getLocale()) }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ uiTrans('contact_form_name', 'Full Name') }} *</label>
                    <input type="text"
                           id="name"
                           name="name"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-is-teal focus:border-transparent transition-colors"
                           placeholder="{{ uiTrans('contact_form_name', 'Enter your full name') }}">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ uiTrans('contact_form_email', 'Email Address') }} *</label>
                    <input type="email"
                           id="email"
                           name="email"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-is-teal focus:border-transparent transition-colors"
                           placeholder="{{ uiTrans('contact_form_email', 'your.email@example.com') }}">
                </div>
            </div>
            
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">{{ uiTrans('contact_form_subject', 'Subject') }} *</label>
                <input type="text"
                       id="subject"
                       name="subject"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-is-teal focus:border-transparent transition-colors"
                       placeholder="{{ uiTrans('contact_form_subject', 'What is this regarding?') }}">
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">{{ uiTrans('contact_form_message', 'Message') }} *</label>
                <textarea id="message"
                          name="message"
                          rows="5"
                          required
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-is-teal focus:border-transparent transition-colors"
                          placeholder="{{ uiTrans('contact_form_message', 'Tell us more about your inquiry...') }}"></textarea>
            </div>
        </div>
        
        <div class="flex items-center justify-end space-x-4 mt-6">
            <button type="submit" 
                    class="w-full md:w-auto flex items-center justify-center rounded-lg border border-transparent bg-is-teal px-8 py-3 text-base font-medium text-white hover:bg-is-teal/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-is-teal transition-colors">
                {{ uiTrans('contact_form_send', 'Send Message') }}
            </button>
        </div>
    </form>
    </div>
    
    <!-- Contact Information -->
    <div class="space-y-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ uiTrans('contact_info_title', 'Contact Information') }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Phone -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-is-teal/20 mb-4">
                        <span class="material-symbols-outlined text-2xl text-is-teal">phone</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ uiTrans('contact_phone', 'Phone') }}</h3>
                        <p class="text-gray-600">+62 812-3456-7890</p>
                        <a href="tel:+6281234567890" 
                           class="inline-flex items-center text-is-teal hover:text-is-teal/80 mt-2">
                            <span class="material-symbols-outlined text-sm">call</span>
                            <span class="ml-1">{{ uiTrans('contact_call_now', 'Call Now') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Email -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-is-teal/20 mb-4">
                        <span class="material-symbols-outlined text-2xl text-is-teal">mail</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ uiTrans('contact_email', 'Email') }}</h3>
                        <p class="text-gray-600">info@is-dept.edu</p>
                        <a href="mailto:info@is-dept.edu" 
                           class="inline-flex items-center text-is-teal hover:text-is-teal/80 mt-2">
                            <span class="material-symbols-outlined text-sm">send</span>
                            <span class="ml-1">{{ uiTrans('contact_send_email', 'Send Email') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Address -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center md:col-span-2">
                <div class="flex flex-col items-center justify-center">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-is-teal/20 mb-4">
                        <span class="material-symbols-outlined text-2xl text-is-teal">location_on</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ uiTrans('contact_address', 'Office Address') }}</h3>
                        <p class="text-gray-600">123 University Avenue<br/>Tech Building, Room 456<br/>Campus City, 12345</p>
                        <a href="https://maps.google.com/?q=123+University+Avenue+Tech+Building" 
                           target="_blank"
                           class="inline-flex items-center text-is-teal hover:text-is-teal/80 mt-2">
                            <span class="material-symbols-outlined text-sm">map</span>
                            <span class="ml-1">{{ uiTrans('contact_get_directions', 'Get Directions') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="bg-white rounded-xl border border-gray-200 p-8 mt-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ uiTrans('contact_quick_links', 'Quick Links') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('guest.home.localized', app()->getLocale()) }}"
           class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
            <span class="material-symbols-outlined text-2xl text-gray-600 group-hover:text-is-teal">home</span>
            <div class="ml-3">
                <h3 class="font-semibold text-gray-900">{{ uiTrans('nav_home', 'Home') }}</h3>
                <p class="text-sm text-gray-600">{{ uiTrans('contact_back_home', 'Back to main page') }}</p>
            </div>
        </a>
        
        <a href="{{ route('guest.articles.localized', app()->getLocale()) }}"
           class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
            <span class="material-symbols-outlined text-2xl text-gray-600 group-hover:text-is-teal">article</span>
            <div class="ml-3">
                <h3 class="font-semibold text-gray-900">{{ uiTrans('nav_articles', 'Articles') }}</h3>
                <p class="text-sm text-gray-600">{{ uiTrans('contact_read_articles', 'Read our latest news and updates') }}</p>
            </div>
        </a>
        
        <a href="{{ route('guest.lecturers.localized', app()->getLocale()) }}"
           class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
            <span class="material-symbols-outlined text-2xl text-gray-600 group-hover:text-is-teal">school</span>
            <div class="ml-3">
                <h3 class="font-semibold text-gray-900">{{ uiTrans('nav_faculty', 'Faculty') }}</h3>
                <p class="text-sm text-gray-600">{{ uiTrans('contact_meet_faculty', 'Meet our teaching staff') }}</p>
            </div>
        </a>
    </div>
</div>
@endsection