<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create admin user
$user = new App\Models\User();
$user->name = 'Admin User';
$user->email = 'admin@example.com';
$user->password = bcrypt('password');
$user->email_verified_at = now();
$user->save();

$adminRole = App\Models\Role::where('name', 'admin')->first();
$user->roles()->attach($adminRole);

echo "Admin user created successfully!\n";
echo "Email: admin@example.com\n";
echo "Password: password\n";