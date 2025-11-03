<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create new user
$user = new App\Models\User();
$user->name = 'Edi Purwanto';
$user->email = 'edipurwanto88@gmail.com';
$user->password = bcrypt('12345676');
$user->email_verified_at = now();
$user->is_active = true;
$user->save();

// Get the default user role (or create one if it doesn't exist)
$userRole = App\Models\Role::where('slug', 'user')->first();
if (!$userRole) {
    $userRole = new App\Models\Role();
    $userRole->name = 'User';
    $userRole->slug = 'user';
    $userRole->save();
}

// Assign the user role to the new user
$user->roles()->attach($userRole);

echo "User created successfully!\n";
echo "Email: edipurwanto88@gmail.com\n";
echo "Password: 12345676\n";
echo "Name: Edi Purwanto\n";
echo "Role: User\n";