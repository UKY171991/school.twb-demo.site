<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamType;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examTypes = [
            [
                'name' => 'Half Yearly',
                'code' => 'HY',
                'description' => 'Half yearly examination conducted in the middle of academic year',
                'duration_days' => 7,
                'weightage' => 50.00,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Annual',
                'code' => 'AN',
                'description' => 'Annual examination conducted at the end of academic year',
                'duration_days' => 10,
                'weightage' => 50.00,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'First Term',
                'code' => 'T1',
                'description' => 'First term examination',
                'duration_days' => 5,
                'weightage' => 33.33,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Second Term',
                'code' => 'T2',
                'description' => 'Second term examination',
                'duration_days' => 5,
                'weightage' => 33.33,
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Third Term',
                'code' => 'T3',
                'description' => 'Third term examination',
                'duration_days' => 5,
                'weightage' => 33.33,
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Unit Test 1',
                'code' => 'UT1',
                'description' => 'First unit test',
                'duration_days' => 2,
                'weightage' => 20.00,
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'name' => 'Unit Test 2',
                'code' => 'UT2',
                'description' => 'Second unit test',
                'duration_days' => 2,
                'weightage' => 20.00,
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'name' => 'Monthly Test',
                'code' => 'MT',
                'description' => 'Monthly assessment test',
                'duration_days' => 1,
                'weightage' => 15.00,
                'is_active' => true,
                'sort_order' => 8
            ],
            [
                'name' => 'Pre-Board',
                'code' => 'PB',
                'description' => 'Pre-board examination for final preparation',
                'duration_days' => 8,
                'weightage' => 100.00,
                'is_active' => true,
                'sort_order' => 9
            ]
        ];

        foreach ($examTypes as $examType) {
            ExamType::firstOrCreate(
                ['code' => $examType['code']],
                $examType
            );
        }
    }
}
