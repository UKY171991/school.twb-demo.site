<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MultiSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Multi-School System Seeding...');

        // Seed in order of dependencies
        $this->call([
            SchoolSeeder::class,
            UserRoleSeeder::class,
            AcademicDataSeeder::class,
        ]);

        $this->command->info('Multi-School System Seeding completed successfully!');
        
        // Display summary
        $schoolCount = \App\Models\School::count();
        $userCount = \App\Models\User::count();
        $studentCount = \App\Models\Student::count();
        $teacherCount = \App\Models\Teacher::count();
        $parentCount = \App\Models\ParentModel::count();
        $classCount = \App\Models\ClassModel::count();
        $subjectCount = \App\Models\Subject::count();
        $attendanceCount = \App\Models\Attendance::count();
        $gradeCount = \App\Models\Grade::count();
        $notificationCount = \App\Models\SystemNotification::count();
        $widgetCount = \App\Models\DashboardWidget::count();

        $this->command->info('=== SEEDING SUMMARY ===');
        $this->command->info("Schools: {$schoolCount}");
        $this->command->info("Users: {$userCount}");
        $this->command->info("Students: {$studentCount}");
        $this->command->info("Teachers: {$teacherCount}");
        $this->command->info("Parents: {$parentCount}");
        $this->command->info("Classes: {$classCount}");
        $this->command->info("Subjects: {$subjectCount}");
        $this->command->info("Attendance Records: {$attendanceCount}");
        $this->command->info("Grade Records: {$gradeCount}");
        $this->command->info("Notifications: {$notificationCount}");
        $this->command->info("Dashboard Widgets: {$widgetCount}");
        $this->command->info('======================');
    }
}
