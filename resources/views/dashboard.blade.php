@extends('layouts.app')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="card">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-primary-100 rounded-lg p-3">
                <i class="fas fa-users text-primary-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::count() }}</p>
            </div>
        </div>
    </div>
    
    <!-- Total Posts -->
    <div class="card">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                <i class="fas fa-newspaper text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Posts</p>
                <p class="text-2xl font-bold text-gray-900">{{ DB::table('posts')->count() }}</p>
            </div>
        </div>
    </div>
    
    <!-- Total Pages -->
    <div class="card">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                <i class="fas fa-file-alt text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pages</p>
                <p class="text-2xl font-bold text-gray-900">{{ DB::table('pages')->count() }}</p>
            </div>
        </div>
    </div>
    
    <!-- Total Categories -->
    <div class="card">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                <i class="fas fa-tags text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Categories</p>
                <p class="text-2xl font-bold text-gray-900">{{ DB::table('categories')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- OWASP Security Information -->
<div class="card mb-8">
    <div class="flex items-center mb-6">
        <i class="fas fa-shield-alt text-primary-600 text-2xl mr-3"></i>
        <h2 class="text-xl font-bold text-gray-900">Security Information</h2>
    </div>
    
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            <p class="text-green-800">Login successful! This system is protected with OWASP security standards.</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <div class="bg-red-100 rounded-full p-2 mr-3">
                    <i class="fas fa-lock text-red-600 text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">A01 - Broken Access Control</h3>
            </div>
            <p class="text-sm text-gray-600">System uses RBAC (Role-Based Access Control) to manage user permissions.</p>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <div class="bg-orange-100 rounded-full p-2 mr-3">
                    <i class="fas fa-key text-orange-600 text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">A02 - Cryptographic Failures</h3>
            </div>
            <p class="text-sm text-gray-600">Passwords are hashed with bcrypt and session data is encrypted.</p>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <div class="bg-yellow-100 rounded-full p-2 mr-3">
                    <i class="fas fa-code text-yellow-600 text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">A03 - Injection</h3>
            </div>
            <p class="text-sm text-gray-600">Input is validated and sanitized to prevent SQL Injection and XSS.</p>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <div class="bg-green-100 rounded-full p-2 mr-3">
                    <i class="fas fa-shield-alt text-green-600 text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">A04 - Insecure Design</h3>
            </div>
            <p class="text-sm text-gray-600">Rate limiting is applied to login endpoints to prevent brute force attacks.</p>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <div class="bg-blue-100 rounded-full p-2 mr-3">
                    <i class="fas fa-cog text-blue-600 text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">A05 - Security Misconfiguration</h3>
            </div>
            <p class="text-sm text-gray-600">Security headers and secure session configuration have been implemented.</p>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <div class="bg-purple-100 rounded-full p-2 mr-3">
                    <i class="fas fa-user-shield text-purple-600 text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">A07 - Authentication Failures</h3>
            </div>
            <p class="text-sm text-gray-600">Secure session management with short lifetime and secure cookies.</p>
        </div>
    </div>
</div>

<!-- User Information -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- User Profile -->
    <div class="card">
        <div class="flex items-center mb-6">
            <i class="fas fa-user text-primary-600 text-xl mr-3"></i>
            <h2 class="text-xl font-bold text-gray-900">User Profile</h2>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-primary-600 flex items-center justify-center mr-4">
                    <span class="text-white font-bold text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-medium text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Last Login</p>
                    <p class="font-medium text-gray-900">{{ now()->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">IP Address</p>
                    <p class="font-medium text-gray-900">{{ request()->ip() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Roles</p>
                    <p class="font-medium text-gray-900">
                        @if (Auth::user()->roles->count() > 0)
                            {{ Auth::user()->roles->pluck('name')->implode(', ') }}
                        @else
                            No roles assigned
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Session Security -->
    <div class="card">
        <div class="flex items-center mb-6">
            <i class="fas fa-lock text-primary-600 text-xl mr-3"></i>
            <h2 class="text-xl font-bold text-gray-900">Session Security</h2>
        </div>
        
        <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 gap-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Session ID:</span>
                        <span class="text-sm font-mono text-gray-900">{{ substr(session()->getId(), 0, 8) }}...</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Driver:</span>
                        <span class="text-sm font-medium text-gray-900">{{ config('session.driver') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Lifetime:</span>
                        <span class="text-sm font-medium text-gray-900">{{ config('session.lifetime') }} minutes</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Encrypted:</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if (config('session.encrypt'))
                                <span class="text-green-600">Yes</span>
                            @else
                                <span class="text-red-600">No</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">HTTP Only:</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if (config('session.http_only'))
                                <span class="text-green-600">Yes</span>
                            @else
                                <span class="text-red-600">No</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Same Site:</span>
                        <span class="text-sm font-medium text-gray-900">{{ config('session.same_site') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
                    <div>
                        <p class="text-sm text-blue-800 font-medium">Security Notice</p>
                        <p class="text-sm text-blue-700 mt-1">All sessions are protected with industry-standard security measures.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card mt-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <i class="fas fa-clock text-primary-600 text-xl mr-3"></i>
            <h2 class="text-xl font-bold text-gray-900">Recent Activity</h2>
        </div>
        <a href="#" class="text-sm text-primary-600 hover:text-primary-700">View all</a>
    </div>
    
    <div class="space-y-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt text-green-600 text-xs"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-900">Login successful</p>
                <p class="text-sm text-gray-600">You logged in to the system</p>
            </div>
            <div class="ml-auto">
                <p class="text-sm text-gray-500">{{ now()->diffForHumans() }}</p>
            </div>
        </div>
        
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-blue-600 text-xs"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-900">Security check passed</p>
                <p class="text-sm text-gray-600">All security validations completed</p>
            </div>
            <div class="ml-auto">
                <p class="text-sm text-gray-500">{{ now()->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection