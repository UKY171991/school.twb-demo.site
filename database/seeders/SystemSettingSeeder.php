<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // School Information
            [
                'key' => 'school_name',
                'value' => 'SchoolMS',
                'type' => 'string',
                'group' => 'school',
                'label' => 'School Name',
                'description' => 'The name of the school',
            ],
            [
                'key' => 'academic_year',
                'value' => '2024-2025',
                'type' => 'string',
                'group' => 'school',
                'label' => 'Academic Year',
                'description' => 'Current academic year',
            ],
            [
                'key' => 'school_address',
                'value' => '123 Education Street, Learning City, LC 12345',
                'type' => 'string',
                'group' => 'school',
                'label' => 'School Address',
                'description' => 'Physical address of the school',
            ],
            [
                'key' => 'school_phone',
                'value' => '+1-555-0123',
                'type' => 'string',
                'group' => 'school',
                'label' => 'School Phone',
                'description' => 'Main phone number',
            ],
            [
                'key' => 'school_email',
                'value' => 'info@schoolms.edu',
                'type' => 'string',
                'group' => 'school',
                'label' => 'School Email',
                'description' => 'Main email address',
            ],
            [
                'key' => 'school_website',
                'value' => 'https://www.schoolms.edu',
                'type' => 'string',
                'group' => 'school',
                'label' => 'School Website',
                'description' => 'School website URL',
            ],
            [
                'key' => 'school_principal',
                'value' => 'Dr. Sarah Johnson',
                'type' => 'string',
                'group' => 'school',
                'label' => 'Principal Name',
                'description' => 'Name of the school principal',
            ],

            // Grading Settings
            [
                'key' => 'pass_percentage',
                'value' => '33',
                'type' => 'float',
                'group' => 'grading',
                'label' => 'Pass Percentage',
                'description' => 'Minimum percentage required to pass',
            ],
            [
                'key' => 'grade_calculation_method',
                'value' => 'percentage',
                'type' => 'string',
                'group' => 'grading',
                'label' => 'Grade Calculation Method',
                'description' => 'Method used to calculate grades',
            ],

            // Marking Settings
            [
                'key' => 'decimal_places',
                'value' => '2',
                'type' => 'integer',
                'group' => 'marking',
                'label' => 'Decimal Places',
                'description' => 'Number of decimal places for marks',
            ],
            [
                'key' => 'rounding_method',
                'value' => 'round',
                'type' => 'string',
                'group' => 'marking',
                'label' => 'Rounding Method',
                'description' => 'Method used for rounding marks',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
