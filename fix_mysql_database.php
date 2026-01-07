<?php

/**
 * MySQL Database Fix Script
 * 
 * This script fixes the MySQL database by adding missing columns (role, created_by)
 * and creates the master user. Run this on your production server after deploying.
 * 
 * Usage: php fix_mysql_database.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MySQL Database Fix Script ===\n\n";

try {
    // Switch to MySQL connection
    \Illuminate\Support\Facades\DB::connection('mysql')->getPdo();
    echo "✓ MySQL connection successful\n";
    
    // Check if users table exists
    $tables = \Illuminate\Support\Facades\DB::connection('mysql')->select("SHOW TABLES LIKE 'users'");
    if (empty($tables)) {
        echo "❌ Users table not found. Please run migrations first.\n";
        exit(1);
    }
    echo "✓ Users table found\n";
    
    // Check and add role column
    $roleColumn = \Illuminate\Support\Facades\DB::connection('mysql')->select("
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'users' 
        AND COLUMN_NAME = 'role'
    ");
    
    if (empty($roleColumn)) {
        echo "Adding 'role' column...\n";
        \Illuminate\Support\Facades\DB::connection('mysql')->statement("
            ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT 'user' AFTER email
        ");
        echo "✓ Role column added\n";
    } else {
        echo "✓ Role column already exists\n";
    }
    
    // Check and add created_by column
    $createdByColumn = \Illuminate\Support\Facades\DB::connection('mysql')->select("
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'users' 
        AND COLUMN_NAME = 'created_by'
    ");
    
    if (empty($createdByColumn)) {
        echo "Adding 'created_by' column...\n";
        \Illuminate\Support\Facades\DB::connection('mysql')->statement("
            ALTER TABLE users ADD COLUMN created_by INT NULL AFTER role
        ");
        echo "✓ Created_by column added\n";
    } else {
        echo "✓ Created_by column already exists\n";
    }
    
    // Update existing users to have default role (admin for registration)
    \Illuminate\Support\Facades\DB::connection('mysql')->statement("
        UPDATE users SET role = 'admin' WHERE role IS NULL OR role = ''
    ");
    echo "✓ Updated existing users with default admin role\n";
    
    // Create or update master user
    $masterUser = \Illuminate\Support\Facades\DB::connection('mysql')->table('users')
        ->where('email', 'umakant171991@gmail.com')
        ->first();
    
    if ($masterUser) {
        \Illuminate\Support\Facades\DB::connection('mysql')->table('users')
            ->where('email', 'umakant171991@gmail.com')
            ->update(['role' => 'master']);
        echo "✓ Updated umakant171991@gmail.com to master role\n";
    } else {
        \Illuminate\Support\Facades\DB::connection('mysql')->table('users')->insert([
            'name' => 'Umakant',
            'email' => 'umakant171991@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'master',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✓ Created master user umakant171991@gmail.com\n";
    }
    
    echo "\n=== Database Fix Complete! ===\n";
    echo "✓ MySQL database is now ready for use\n";
    echo "✓ Master user created/updated: umakant171991@gmail.com\n";
    echo "✓ Default password: password (change after first login)\n";
    echo "\nYou can now use the application with MySQL database!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nPlease check:\n";
    echo "1. MySQL server is running\n";
    echo "2. Database credentials in .env are correct\n";
    echo "3. Database exists on the server\n";
    echo "4. User has proper permissions\n";
    exit(1);
}
