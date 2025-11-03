<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test authentication
$credentials = [
    'email' => 'admin@example.com',
    'password' => 'password'
];

if (Auth::attempt($credentials)) {
    echo "✅ Login berhasil!\n";
    echo "User: " . Auth::user()->name . "\n";
    echo "Email: " . Auth::user()->email . "\n";
    
    // Check roles
    $roles = Auth::user()->roles;
    echo "Roles:\n";
    foreach ($roles as $role) {
        echo "  - " . $role->name . "\n";
    }
} else {
    echo "❌ Login gagal!\n";
}