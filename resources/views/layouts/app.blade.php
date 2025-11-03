<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CMS Kampus') - Admin Panel</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Main Container -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside x-show="sidebarOpen || window.innerWidth >= 1024" 
               x-transition:enter="transition ease-in-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg lg:static lg:inset-0 lg:translate-x-0"
               :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">CMS Kampus</h1>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                
                <!-- User Management -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="sidebar-link w-full justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-users w-5 h-5 mr-3"></i>
                            User Management
                        </div>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" x-transition class="mt-2 space-y-1">
                        <a href="{{ route('users.index') }}" class="sidebar-link pl-12 text-sm">
                            Users
                        </a>
                        <a href="{{ route('roles.index') }}" class="sidebar-link pl-12 text-sm">
                            Roles
                        </a>
                        <a href="{{ route('permissions.index') }}" class="sidebar-link pl-12 text-sm">
                            Permissions
                        </a>
                    </div>
                </div>
                
                <!-- Content Management -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="sidebar-link w-full justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
                            Content
                        </div>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" x-transition class="mt-2 space-y-1">
                        <a href="{{ route('pages.index') }}" class="sidebar-link pl-12 text-sm {{ request()->routeIs('pages*') ? 'active' : '' }}">
                            Pages
                        </a>
                        <a href="#" class="sidebar-link pl-12 text-sm">
                            Posts
                        </a>
                        <a href="#" class="sidebar-link pl-12 text-sm">
                            Categories
                        </a>
                        <a href="#" class="sidebar-link pl-12 text-sm">
                            Media
                        </a>
                    </div>
                </div>
                
                <!-- Settings -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="sidebar-link w-full justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-cog w-5 h-5 mr-3"></i>
                            Settings
                        </div>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" x-transition class="mt-2 space-y-1">
                        <a href="#" class="sidebar-link pl-12 text-sm">
                            General
                        </a>
                        <a href="#" class="sidebar-link pl-12 text-sm">
                            Languages
                        </a>
                        <a href="#" class="sidebar-link pl-12 text-sm">
                            Menus
                        </a>
                    </div>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Left side -->
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="hidden md:block">
                            <div class="relative">
                                <input type="text" placeholder="Search..." 
                                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Notifications -->
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="relative p-2 text-gray-600 hover:text-gray-900">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <div x-show="dropdownOpen" 
                                 @click.away="dropdownOpen = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <div class="p-4 hover:bg-gray-50 cursor-pointer">
                                        <p class="text-sm text-gray-900">New user registered</p>
                                        <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                                    <span class="text-white font-medium">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <span class="hidden md:block text-gray-700">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </button>
                            
                            <div x-show="dropdownOpen" 
                                 @click.away="dropdownOpen = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i> Settings
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Page Header -->
                        @if(request()->routeIs('dashboard'))
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                            <p class="mt-1 text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
                        </div>
                        @else
                        <div class="mb-8">
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2">
                                    <li>
                                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-home"></i>
                                        </a>
                                    </li>
                                    <li class="text-gray-400">/</li>
                                    <li class="text-gray-900">@yield('breadcrumb')</li>
                                </ol>
                            </nav>
                            <h1 class="mt-2 text-2xl font-bold text-gray-900">@yield('title')</h1>
                        </div>
                        @endif
                        
                        <!-- Content -->
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Flash Messages -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition
         class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition
         class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    <!-- CKEditor (loaded before app.js) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.16.2/ckeditor.js"></script>
    
    <!-- JavaScript -->
    @vite(['resources/js/app.js'])
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>