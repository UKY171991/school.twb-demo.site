<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PopulateDummyData extends Command
{
    protected $signature = 'school:populate-dummy-data {--fresh : Fresh migration and seed}';

    protected $description = 'Populate the database with comprehensive dummy data for school management system';

    public function handle()
    {
        if ($this->option('fresh')) {
            $this->info('Running fresh migration...');
            Artisan::call('migrate:fresh');
            $this->info('Migration completed.');
        }

        $this->info('Populating database with dummy data...');

        // Check if data already exists
        $schoolCount = DB::table('schools')->count();
        if ($schoolCount > 0) {
            if (! $this->confirm('Database already contains data. Do you want to continue? This will add more data.')) {
                $this->info('Operation cancelled.');

                return;
            }
        }

        // Run the seeder
        Artisan::call('db:seed', ['--class' => 'ComprehensiveDummyDataSeeder']);

        $this->info('✅ Dummy data populated successfully!');
        $this->newLine();

        // Display summary
        $this->displaySummary();

        $this->newLine();
        $this->info('🎉 Your school management system is now ready with comprehensive test data!');
        $this->info('You can login with: admin@school.com / password');
    }

    private function displaySummary()
    {
        $this->info('📊 Data Summary:');
        $this->table(
            ['Entity', 'Count'],
            [
                ['Schools', DB::table('schools')->count()],
                ['Grades/Classes', DB::table('grades')->count()],
                ['Teachers', DB::table('teachers')->count()],
                ['Students', DB::table('students')->count()],
                ['Subjects', DB::table('subjects')->count()],
                ['Exam Types', DB::table('exam_types')->count()],
                ['Marksheets', DB::table('marksheets')->count()],
                ['Attendance Records', DB::table('attendances')->count()],
                ['Individual Marks', DB::table('marks')->count()],
            ]
        );
    }
}
