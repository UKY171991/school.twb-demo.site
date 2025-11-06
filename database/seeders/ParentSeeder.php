<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\School;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        
        foreach ($schools as $school) {
            $this->seedParentsForSchool($school);
        }
        
        $this->command->info('Parent seeder completed successfully!');
    }

    private function seedParentsForSchool(School $school)
    {
        // Get students who don't have parents yet
        $studentsWithoutParents = Student::where('school_id', $school->id)
            ->whereNull('parent_id')
            ->get();

        if ($studentsWithoutParents->isEmpty()) {
            return;
        }

        $parentData = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1-555-0101',
                'relationship' => 'father',
                'occupation' => 'Engineer',
                'company' => 'Tech Solutions Inc.',
            ],
            [
                'first_name' => 'Mary',
                'last_name' => 'Johnson',
                'email' => 'mary.johnson@example.com',
                'phone' => '+1-555-0102',
                'relationship' => 'mother',
                'occupation' => 'Teacher',
                'company' => 'Local Elementary School',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'david.brown@example.com',
                'phone' => '+1-555-0103',
                'relationship' => 'father',
                'occupation' => 'Doctor',
                'company' => 'City Hospital',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Davis',
                'email' => 'sarah.davis@example.com',
                'phone' => '+1-555-0104',
                'relationship' => 'mother',
                'occupation' => 'Nurse',
                'company' => 'Community Health Center',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Wilson',
                'email' => 'michael.wilson@example.com',
                'phone' => '+1-555-0105',
                'relationship' => 'father',
                'occupation' => 'Business Owner',
                'company' => 'Wilson Enterprises',
            ],
        ];

        foreach ($studentsWithoutParents->take(5) as $index => $student) {
            if (!isset($parentData[$index])) {
                break;
            }

            $data = $parentData[$index];
            
            // Create user account for parent
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'user_type' => 'parent',
                'school_id' => $school->id,
                'is_active' => true,
            ]);

            // Create parent profile
            $parent = ParentModel::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'relationship' => $data['relationship'],
                'occupation' => $data['occupation'],
                'company' => $data['company'],
                'annual_income' => rand(30000, 120000),
                'is_primary_contact' => true,
                'address' => $this->generateRandomAddress(),
                'date_of_birth' => now()->subYears(rand(25, 45))->subDays(rand(1, 365)),
                'gender' => $data['relationship'] === 'father' ? 'male' : 'female',
            ]);

            // Associate student with parent
            $student->update(['parent_id' => $parent->id]);

            // Sometimes create a second child for the same parent
            if (rand(0, 1) && $studentsWithoutParents->count() > $index + 1) {
                $secondChild = $studentsWithoutParents->skip($index + 1)->first();
                if ($secondChild && !$secondChild->parent_id) {
                    $secondChild->update(['parent_id' => $parent->id]);
                }
            }
        }
    }

    private function generateRandomAddress(): string
    {
        $streets = [
            'Main Street', 'Oak Avenue', 'Pine Road', 'Maple Drive', 'Cedar Lane',
            'Elm Street', 'Park Avenue', 'First Street', 'Second Avenue', 'Third Street'
        ];
        
        $number = rand(100, 9999);
        $street = $streets[array_rand($streets)];
        $city = 'Springfield';
        $state = 'IL';
        $zip = rand(10000, 99999);
        
        return "{$number} {$street}, {$city}, {$state} {$zip}";
    }
}