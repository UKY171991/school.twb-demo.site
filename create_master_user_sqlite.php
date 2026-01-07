<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Creating Master User in SQLite ===\n\n";

// Find or create the user
$user = \App\Models\User::where('email', 'umakant171991@gmail.com')->first();

if ($user) {
    echo "Found user: " . $user->name . " - Current role: " . $user->role . "\n";
    
    // Update to master role
    $user->role = 'master';
    $user->save();
    
    echo "Updated role to: " . $user->role . "\n";
    echo "User umakant171991@gmail.com is now a master user!\n";
} else {
    echo "User with email umakant171991@gmail.com not found. Creating new user...\n";
    
    // Create the user if not found
    $user = \App\Models\User::create([
        'name' => 'Umakant',
        'email' => 'umakant171991@gmail.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'), // Default password
        'role' => 'master',
    ]);
    
    echo "Created new master user: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Password: password (please change this after login)\n";
    echo "Role: " . $user->role . "\n";
}

echo "\n=== SQLite Database Setup Complete! ===\n";
echo "✓ Database file: database/database.sqlite\n";
echo "✓ Master user: umakant171991@gmail.com\n";
echo "✓ Default password: password\n";
echo "✓ Role: master\n";
echo "\nYou can now login and use the application!\n";
