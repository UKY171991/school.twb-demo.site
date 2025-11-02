<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = \App\Models\School::all();

        // Create 1 Super Admin (not tied to any school)
        $superAdmin = \App\Models\User::factory()->superAdmin()->create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@school.edu',
            'school_id' => null,
        ]);

        // Create default dashboard widgets for super admin
        \App\Models\DashboardWidget::createDefaultWidgets($superAdmin->id, 'super_admin');

        $this->command->info('Created Super Admin: ' . $superAdmin->email);

        foreach ($schools as $school) {
            // Create 1 Admin per school
            $admin = \App\Models\User::factory()->admin()->create([
                'name' => 'School Administrator - ' . $school->name,
                'email' => 'admin' . $school->id . '@' . strtolower(str_replace(' ', '', $school->name)) . '.edu',
                'school_id' => $school->id,
            ]);

            // Create default dashboard widgets for admin
            \App\Models\DashboardWidget::createDefaultWidgets($admin->id, 'admin');

            // Create 5-8 Teachers per school
            $teacherCount = rand(5, 8);
            for ($i = 0; $i < $teacherCount; $i++) {
                $teacherUser = \App\Models\User::factory()->teacher()->create([
                    'school_id' => $school->id,
                ]);

                $teacher = \App\Models\Teacher::factory()->create([
                    'school_id' => $school->id,
                    'user_id' => $teacherUser->id,
                    'email' => $teacherUser->email,
                ]);

                // Create default dashboard widgets for teacher
                \App\Models\DashboardWidget::createDefaultWidgets($teacherUser->id, 'teacher');
            }

            // Create 15-25 Parents per school first
            $parentCount = rand(15, 25);
            $parents = collect();
            for ($i = 0; $i < $parentCount; $i++) {
                $parentUser = \App\Models\User::factory()->parent()->create([
                    'school_id' => $school->id,
                ]);

                $parent = \App\Models\ParentModel::factory()->create([
                    'school_id' => $school->id,
                    'user_id' => $parentUser->id,
                    'email' => $parentUser->email,
                ]);

                $parents->push($parent);

                // Create default dashboard widgets for parent
                \App\Models\DashboardWidget::createDefaultWidgets($parentUser->id, 'parent');
            }

            // Create some classes first for students
            $classes = \App\Models\ClassModel::factory(rand(8, 12))->create([
                'school_id' => $school->id,
                'teacher_id' => \App\Models\Teacher::where('school_id', $school->id)->inRandomOrder()->first()->id ?? null,
            ]);

            // Create 20-35 Students per school
            $studentCount = rand(20, 35);
            for ($i = 0; $i < $studentCount; $i++) {
                $studentUser = \App\Models\User::factory()->student()->create([
                    'school_id' => $school->id,
                ]);

                $student = \App\Models\Student::factory()->create([
                    'school_id' => $school->id,
                    'user_id' => $studentUser->id,
                    'class_id' => $classes->random()->id,
                    'parent_id' => $parents->random()->id,
                    'email' => $studentUser->email,
                ]);

                // Create default dashboard widgets for student
                \App\Models\DashboardWidget::createDefaultWidgets($studentUser->id, 'student');
            }

            $this->command->info('Created users for school: ' . $school->name);
        }

        // Create some system notifications
        $users = \App\Models\User::all();
        foreach ($users->random(min(20, $users->count())) as $user) {
            \App\Models\SystemNotification::factory(rand(1, 5))->create([
                'user_id' => $user->id,
                'school_id' => $user->school_id,
            ]);
        }

        $this->command->info('Created system notifications for random users.');
    }
}
