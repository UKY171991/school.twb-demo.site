<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PopulateDummyData extends Command
{
    protected $signature = 'school:populate-dummy-data {--fresh : Fresh migration and seed}';

    protected $description = 'Populate the database with basic user accounts for school management system';

    public function handle()
    {
        if ($this->option('fresh')) {
            $this->info('Running fresh migration...');
            Artisan::call('migrate:fresh');
            $this->info('Migration completed.');
        }

        $this->info('Populating database with basic user data...');

        // Check if data already exists
        $userCount = DB::table('users')->count();
        if ($userCount > 0) {
            if (! $this->confirm('Database already contains user data. Do you want to continue? This will add more users.')) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        // Run only the basic DatabaseSeeder (no demo data)
        Artisan::call('db:seed');

        $this->info('✅ Basic user data populated successfully!');
        $this->newLine();

        // Display summary
        $this->displaySummary();

        $this->newLine();
        $this->info('🎉 Your school management system is now ready with basic user accounts!');
        $this->info('You can login with: admin@school.com / password');
    }

    private function displaySummary()
    {
        $this->info('📊 Data Summary:');
        $this->table(
            ['Entity', 'Count'],
            [
                ['Users', DB::table('users')->count()],
                ['Schools', DB::table('schools')->count()],
            ]
        );
    }
}
