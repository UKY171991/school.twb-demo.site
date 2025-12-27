<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GradingSystem;
use App\Models\SystemSetting;
use App\Models\MarkingScheme;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default grading system
        $gradingData = [
            ['grade' => 'A+', 'name' => 'Excellent', 'min_percentage' => 90, 'max_percentage' => 100, 'grade_points' => 4.0, 'is_passing' => true, 'sort_order' => 1],
            ['grade' => 'A', 'name' => 'Very Good', 'min_percentage' => 80, 'max_percentage' => 89, 'grade_points' => 3.7, 'is_passing' => true, 'sort_order' => 2],
            ['grade' => 'B+', 'name' => 'Good', 'min_percentage' => 70, 'max_percentage' => 79, 'grade_points' => 3.3, 'is_passing' => true, 'sort_order' => 3],
            ['grade' => 'B', 'name' => 'Above Average', 'min_percentage' => 60, 'max_percentage' => 69, 'grade_points' => 3.0, 'is_passing' => true, 'sort_order' => 4],
            ['grade' => 'C+', 'name' => 'Average', 'min_percentage' => 50, 'max_percentage' => 59, 'grade_points' => 2.3, 'is_passing' => true, 'sort_order' => 5],
            ['grade' => 'C', 'name' => 'Below Average', 'min_percentage' => 40, 'max_percentage' => 49, 'grade_points' => 2.0, 'is_passing' => true, 'sort_order' => 6],
            ['grade' => 'D', 'name' => 'Poor', 'min_percentage' => 33, 'max_percentage' => 39, 'grade_points' => 1.0, 'is_passing' => true, 'sort_order' => 7],
            ['grade' => 'F', 'name' => 'Fail', 'min_percentage' => 0, 'max_percentage' => 32, 'grade_points' => 0.0, 'is_passing' => false, 'sort_order' => 8],
        ];

        foreach ($gradingData as $data) {
            GradingSystem::firstOrCreate(
                ['grade' => $data['grade']],
                $data
            );
        }

        // Create default system settings
        $settings = [
            // School Information
            ['key' => 'school_name', 'value' => 'ABC School', 'type' => 'string', 'group' => 'school', 'label' => 'School Name'],
            ['key' => 'school_address', 'value' => '123 Education Street, Learning City', 'type' => 'string', 'group' => 'school', 'label' => 'School Address'],
            ['key' => 'school_phone', 'value' => '+1234567890', 'type' => 'string', 'group' => 'school', 'label' => 'School Phone'],
            ['key' => 'school_email', 'value' => 'info@abcschool.edu', 'type' => 'string', 'group' => 'school', 'label' => 'School Email'],
            ['key' => 'academic_year', 'value' => '2024-2025', 'type' => 'string', 'group' => 'school', 'label' => 'Academic Year'],
            
            // Grading Settings
            ['key' => 'current_grading_scheme', 'value' => 'default', 'type' => 'string', 'group' => 'grading', 'label' => 'Current Grading Scheme'],
            ['key' => 'pass_percentage', 'value' => '33', 'type' => 'float', 'group' => 'grading', 'label' => 'Pass Percentage'],
            ['key' => 'grade_calculation_method', 'value' => 'percentage', 'type' => 'string', 'group' => 'grading', 'label' => 'Grade Calculation Method'],
            
            // Marking Settings
            ['key' => 'current_marking_scheme', 'value' => 'percentage', 'type' => 'string', 'group' => 'marking', 'label' => 'Current Marking Scheme'],
            ['key' => 'decimal_places', 'value' => '2', 'type' => 'integer', 'group' => 'marking', 'label' => 'Decimal Places'],
            ['key' => 'rounding_method', 'value' => 'round', 'type' => 'string', 'group' => 'marking', 'label' => 'Rounding Method'],
            ['key' => 'show_grade_points', 'value' => 'false', 'type' => 'boolean', 'group' => 'marking', 'label' => 'Show Grade Points'],
            ['key' => 'show_class_rank', 'value' => 'false', 'type' => 'boolean', 'group' => 'marking', 'label' => 'Show Class Rank'],
            ['key' => 'allow_negative_marks', 'value' => 'false', 'type' => 'boolean', 'group' => 'marking', 'label' => 'Allow Negative Marks'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Create default marking schemes
        $markingSchemes = [
            [
                'name' => 'Percentage Based',
                'type' => 'percentage',
                'configuration' => [
                    'grades' => [
                        ['grade' => 'A+', 'min' => 90, 'max' => 100],
                        ['grade' => 'A', 'min' => 80, 'max' => 89],
                        ['grade' => 'B+', 'min' => 70, 'max' => 79],
                        ['grade' => 'B', 'min' => 60, 'max' => 69],
                        ['grade' => 'C+', 'min' => 50, 'max' => 59],
                        ['grade' => 'C', 'min' => 40, 'max' => 49],
                        ['grade' => 'D', 'min' => 33, 'max' => 39],
                        ['grade' => 'F', 'min' => 0, 'max' => 32],
                    ]
                ],
                'description' => 'Standard percentage-based grading system',
                'is_active' => true
            ]
        ];

        foreach ($markingSchemes as $scheme) {
            MarkingScheme::firstOrCreate(
                ['name' => $scheme['name']],
                $scheme
            );
        }
    }
}
