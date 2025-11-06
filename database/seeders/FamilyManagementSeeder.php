<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FamilyProfile;
use App\Models\EmergencyContact;
use App\Models\StudentPermission;
use App\Models\SchoolActivity;
use App\Models\User;
use App\Models\Student;
use App\Models\School;
use Carbon\Carbon;

class FamilyManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        
        foreach ($schools as $school) {
            $this->seedFamilyProfiles($school);
            $this->seedSchoolActivities($school);
            $this->seedStudentPermissions($school);
        }
        
        $this->command->info('Family Management seeder completed successfully!');
    }

    private function seedFamilyProfiles(School $school)
    {
        $parents = User::where('school_id', $school->id)
            ->where('user_type', 'parent')
            ->get();
        
        foreach ($parents as $parent) {
            // Skip if family profile already exists
            if (FamilyProfile::where('parent_id', $parent->id)->exists()) {
                continue;
            }
            
            // Create family profile
            $familyProfile = FamilyProfile::create([
                'parent_id' => $parent->id,
                'family_name' => $parent->name . ' Family',
                'primary_contact_name' => $parent->name,
                'primary_contact_phone' => $parent->phone ?? $this->generatePhoneNumber(),
                'primary_contact_email' => $parent->email,
                'secondary_contact_name' => $this->getRandomSecondaryContactName(),
                'secondary_contact_phone' => $this->generatePhoneNumber(),
                'secondary_contact_email' => $this->generateSecondaryEmail($parent->email),
                'home_address' => $this->getRandomAddress(),
                'work_address' => $this->getRandomAddress(),
                'medical_information' => rand(0, 1) ? $this->getRandomMedicalInfo() : null,
                'special_instructions' => rand(0, 1) ? $this->getRandomSpecialInstructions() : null,
                'notification_preferences' => FamilyProfile::getDefaultNotificationPreferences(),
                'communication_preferences' => FamilyProfile::getDefaultCommunicationPreferences(),
                'privacy_settings' => FamilyProfile::getDefaultPrivacySettings(),
            ]);

            // Create emergency contacts
            $this->createEmergencyContacts($parent->id);
        }
    }

    private function createEmergencyContacts(int $parentId)
    {
        $contactCount = rand(2, 4);
        $relationships = ['Grandparent', 'Aunt', 'Uncle', 'Family Friend', 'Neighbor', 'Sibling'];
        
        for ($i = 0; $i < $contactCount; $i++) {
            EmergencyContact::create([
                'parent_id' => $parentId,
                'name' => $this->getRandomContactName(),
                'relationship' => $relationships[array_rand($relationships)],
                'phone_primary' => $this->generatePhoneNumber(),
                'phone_secondary' => rand(0, 1) ? $this->generatePhoneNumber() : null,
                'email' => rand(0, 1) ? $this->generateRandomEmail() : null,
                'address' => rand(0, 1) ? $this->getRandomAddress() : null,
                'is_authorized_pickup' => rand(0, 1),
                'notes' => rand(0, 1) ? $this->getRandomContactNotes() : null,
                'priority_order' => $i + 1,
            ]);
        }
    }

    private function seedSchoolActivities(School $school)
    {
        $admins = User::where('school_id', $school->id)
            ->where('user_type', 'admin')
            ->get();
        
        if ($admins->isEmpty()) {
            return;
        }

        $activities = [
            [
                'title' => 'Science Museum Field Trip',
                'description' => 'Educational visit to the local science museum with interactive exhibits.',
                'activity_type' => 'field_trip',
                'requires_payment' => true,
                'payment_amount' => 25.00,
                'medical_form_required' => false,
                'transport_provided' => true,
            ],
            [
                'title' => 'Annual Sports Day',
                'description' => 'School-wide sports competition with various athletic events.',
                'activity_type' => 'sports_event',
                'requires_payment' => false,
                'payment_amount' => null,
                'medical_form_required' => true,
                'transport_provided' => false,
            ],
            [
                'title' => 'School Play Performance',
                'description' => 'Evening performance of the school drama club\'s latest production.',
                'activity_type' => 'school_event',
                'requires_payment' => false,
                'payment_amount' => null,
                'medical_form_required' => false,
                'transport_provided' => false,
            ],
            [
                'title' => 'Fundraising Bake Sale',
                'description' => 'Community bake sale to raise funds for new playground equipment.',
                'activity_type' => 'fundraiser',
                'requires_payment' => false,
                'payment_amount' => null,
                'medical_form_required' => false,
                'transport_provided' => false,
            ],
            [
                'title' => 'Math Competition',
                'description' => 'Inter-school mathematics competition for advanced students.',
                'activity_type' => 'academic',
                'requires_payment' => true,
                'payment_amount' => 15.00,
                'medical_form_required' => false,
                'transport_provided' => true,
            ],
        ];

        foreach ($activities as $activityData) {
            $activityDate = Carbon::now()->addDays(rand(7, 60));
            
            SchoolActivity::create([
                'school_id' => $school->id,
                'title' => $activityData['title'],
                'description' => $activityData['description'],
                'activity_type' => $activityData['activity_type'],
                'activity_date' => $activityDate,
                'start_time' => $activityDate->copy()->setTime(rand(8, 15), [0, 30][rand(0, 1)]),
                'end_time' => $activityDate->copy()->setTime(rand(16, 18), [0, 30][rand(0, 1)]),
                'location' => $this->getRandomLocation(),
                'organizer_id' => $admins->random()->id,
                'max_participants' => rand(20, 100),
                'requires_permission' => true,
                'requires_payment' => $activityData['requires_payment'],
                'payment_amount' => $activityData['payment_amount'],
                'payment_deadline' => $activityData['requires_payment'] ? $activityDate->copy()->subDays(7) : null,
                'permission_deadline' => $activityDate->copy()->subDays(3),
                'medical_form_required' => $activityData['medical_form_required'],
                'transport_provided' => $activityData['transport_provided'],
                'pickup_location' => $activityData['transport_provided'] ? 'School Main Entrance' : null,
                'return_location' => $activityData['transport_provided'] ? 'School Main Entrance' : null,
                'contact_person' => $admins->random()->name,
                'contact_phone' => $this->generatePhoneNumber(),
                'contact_email' => $admins->random()->email,
                'special_instructions' => rand(0, 1) ? $this->getRandomActivityInstructions() : null,
                'is_active' => true,
            ]);
        }
    }

    private function seedStudentPermissions(School $school)
    {
        $students = Student::where('school_id', $school->id)->get();
        $activities = SchoolActivity::where('school_id', $school->id)->get();
        
        if ($students->isEmpty() || $activities->isEmpty()) {
            return;
        }

        foreach ($students->take(10) as $student) {
            $activity = $activities->random();
            
            // Map activity types to permission types
            $permissionTypeMap = [
                'field_trip' => 'field_trip',
                'sports_event' => 'sports_event', 
                'school_event' => 'school_event',
                'fundraiser' => 'school_event',
                'academic' => 'school_event',
                'cultural' => 'school_event'
            ];
            
            StudentPermission::create([
                'student_id' => $student->id,
                'activity_id' => $activity->id,
                'permission_type' => $permissionTypeMap[$activity->activity_type] ?? 'school_event',
                'title' => $activity->title,
                'description' => $activity->description,
                'activity_date' => $activity->activity_date,
                'deadline' => $activity->permission_deadline,
                'status' => ['pending', 'pending', 'approved', 'denied'][rand(0, 3)],
                'parent_notes' => rand(0, 1) ? $this->getRandomParentNotes() : null,
                'teacher_notes' => rand(0, 1) ? $this->getRandomTeacherNotes() : null,
                'requested_at' => Carbon::now()->subDays(rand(1, 14)),
                'responded_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 7)) : null,
                'requires_payment' => $activity->requires_payment,
                'payment_amount' => $activity->payment_amount,
                'payment_deadline' => $activity->payment_deadline,
                'medical_form_required' => $activity->medical_form_required,
                'transport_required' => $activity->transport_provided,
                'pickup_time' => $activity->start_time,
                'return_time' => $activity->end_time,
                'location' => $activity->location,
            ]);
        }
    }

    private function generatePhoneNumber(): string
    {
        return sprintf('(%03d) %03d-%04d', rand(200, 999), rand(200, 999), rand(1000, 9999));
    }

    private function getRandomSecondaryContactName(): string
    {
        $names = [
            'Sarah Johnson', 'Michael Smith', 'Jennifer Brown', 'David Wilson',
            'Lisa Davis', 'Robert Miller', 'Karen Anderson', 'James Taylor'
        ];
        return $names[array_rand($names)];
    }

    private function generateSecondaryEmail(string $primaryEmail): string
    {
        $domain = substr($primaryEmail, strpos($primaryEmail, '@'));
        $prefix = 'secondary' . rand(100, 999);
        return $prefix . $domain;
    }

    private function getRandomAddress(): string
    {
        $addresses = [
            '123 Main Street, Anytown, ST 12345',
            '456 Oak Avenue, Springfield, ST 67890',
            '789 Pine Road, Riverside, ST 54321',
            '321 Elm Street, Lakewood, ST 98765',
            '654 Maple Drive, Hillside, ST 13579'
        ];
        return $addresses[array_rand($addresses)];
    }

    private function getRandomMedicalInfo(): string
    {
        $info = [
            'Child has mild asthma - inhaler available at school office',
            'Allergic to peanuts - EpiPen on file',
            'Takes daily medication for ADHD',
            'No known allergies or medical conditions',
            'Lactose intolerant - please provide alternative milk products'
        ];
        return $info[array_rand($info)];
    }

    private function getRandomSpecialInstructions(): string
    {
        $instructions = [
            'Please call if child will be absent',
            'Child walks home - no pickup required',
            'Grandmother picks up on Wednesdays',
            'Please send homework if absent',
            'Child participates in after-school program'
        ];
        return $instructions[array_rand($instructions)];
    }

    private function getRandomContactName(): string
    {
        $names = [
            'Margaret Thompson', 'William Garcia', 'Patricia Martinez',
            'Charles Rodriguez', 'Barbara Lewis', 'Joseph Lee',
            'Susan Walker', 'Thomas Hall', 'Nancy Allen', 'Daniel Young'
        ];
        return $names[array_rand($names)];
    }

    private function generateRandomEmail(): string
    {
        $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
        $prefix = 'contact' . rand(100, 999);
        return $prefix . '@' . $domains[array_rand($domains)];
    }

    private function getRandomContactNotes(): string
    {
        $notes = [
            'Available during business hours only',
            'Prefers text messages over phone calls',
            'Lives nearby - can respond quickly',
            'Has medical training',
            'Speaks Spanish and English'
        ];
        return $notes[array_rand($notes)];
    }

    private function getRandomLocation(): string
    {
        $locations = [
            'School Gymnasium', 'Science Museum', 'City Park',
            'Community Center', 'School Auditorium', 'Local Library',
            'Sports Complex', 'Art Gallery', 'Nature Center'
        ];
        return $locations[array_rand($locations)];
    }

    private function getRandomActivityInstructions(): string
    {
        $instructions = [
            'Please bring a packed lunch and water bottle',
            'Wear comfortable walking shoes',
            'Bring a jacket as it may be cold',
            'No electronic devices allowed',
            'Parents welcome to volunteer as chaperones'
        ];
        return $instructions[array_rand($instructions)];
    }

    private function getRandomParentNotes(): string
    {
        $notes = [
            'My child is very excited about this activity',
            'Please ensure proper supervision',
            'Child has been looking forward to this event',
            'Thank you for organizing this opportunity',
            'Please call if any issues arise'
        ];
        return $notes[array_rand($notes)];
    }

    private function getRandomTeacherNotes(): string
    {
        $notes = [
            'Student shows great enthusiasm for this activity',
            'Please ensure student follows safety guidelines',
            'Student may need extra encouragement',
            'Excellent opportunity for learning',
            'Student has been well-prepared for this event'
        ];
        return $notes[array_rand($notes)];
    }
}