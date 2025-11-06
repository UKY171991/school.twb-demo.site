<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\School;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        
        foreach ($schools as $school) {
            $classes = ClassModel::where('school_id', $school->id)->get();
            $subjects = Subject::where('school_id', $school->id)->get();
            $teachers = Teacher::where('school_id', $school->id)->get();
            
            if ($classes->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty()) {
                continue;
            }
            
            foreach ($classes as $class) {
                // Create 5-10 assignments per class
                $assignmentCount = rand(5, 10);
                
                for ($i = 0; $i < $assignmentCount; $i++) {
                    $subject = $subjects->random();
                    $teacher = $teachers->random();
                    
                    $assignedDate = Carbon::now()->subDays(rand(1, 30));
                    $dueDate = $assignedDate->copy()->addDays(rand(3, 14));
                    
                    $types = ['homework', 'project', 'quiz', 'exam', 'presentation', 'other'];
                    $type = $types[array_rand($types)];
                    
                    $titles = [
                        'homework' => [
                            'Chapter Review Questions',
                            'Practice Problems Set',
                            'Reading Assignment',
                            'Worksheet Completion',
                            'Study Guide Preparation'
                        ],
                        'project' => [
                            'Research Project',
                            'Science Fair Project',
                            'Group Presentation',
                            'Creative Writing Project',
                            'Art Portfolio'
                        ],
                        'quiz' => [
                            'Weekly Quiz',
                            'Pop Quiz',
                            'Chapter Test',
                            'Vocabulary Quiz',
                            'Math Skills Assessment'
                        ],
                        'exam' => [
                            'Midterm Examination',
                            'Final Exam',
                            'Unit Test',
                            'Comprehensive Assessment',
                            'Semester Exam'
                        ],
                        'presentation' => [
                            'Oral Presentation',
                            'Book Report Presentation',
                            'Science Demonstration',
                            'History Timeline Presentation',
                            'Show and Tell'
                        ],
                        'other' => [
                            'Field Trip Report',
                            'Lab Report',
                            'Essay Assignment',
                            'Creative Assignment',
                            'Special Project'
                        ]
                    ];
                    
                    $title = $titles[$type][array_rand($titles[$type])];
                    
                    $descriptions = [
                        'Complete the assigned tasks and submit on time.',
                        'Follow the instructions carefully and show all work.',
                        'Research the topic thoroughly and cite your sources.',
                        'Work individually unless specified otherwise.',
                        'Use proper formatting and grammar in your submission.',
                        'Include examples and explanations where appropriate.',
                        'Submit both digital and physical copies if required.',
                        'Prepare for class discussion on this topic.',
                        'Review the rubric before starting your work.',
                        'Ask questions if you need clarification.'
                    ];
                    
                    $instructions = [
                        'Read pages 45-67 in your textbook and answer questions 1-15.',
                        'Create a poster presentation on the assigned topic.',
                        'Write a 500-word essay on the given subject.',
                        'Solve all problems in the practice worksheet.',
                        'Prepare a 5-minute presentation for the class.',
                        'Complete the lab experiment and write a report.',
                        'Research three sources and create a bibliography.',
                        'Practice the assigned skills and demonstrate proficiency.',
                        'Create a visual aid to support your presentation.',
                        'Submit your work in the specified format.'
                    ];
                    
                    Assignment::create([
                        'school_id' => $school->id,
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $teacher->id,
                        'title' => $title,
                        'description' => $descriptions[array_rand($descriptions)],
                        'instructions' => $instructions[array_rand($instructions)],
                        'type' => $type,
                        'assigned_date' => $assignedDate,
                        'due_date' => $dueDate,
                        'due_time' => rand(0, 1) ? Carbon::createFromTime(rand(8, 17), [0, 30][rand(0, 1)]) : null,
                        'total_marks' => [10, 20, 25, 50, 100][rand(0, 4)],
                        'status' => ['published', 'published', 'published', 'draft'][rand(0, 3)], // Mostly published
                        'allow_late_submission' => rand(0, 1),
                        'late_penalty_percentage' => rand(0, 1) ? rand(5, 20) : 0,
                        'submission_instructions' => 'Submit your completed work through the online portal or hand in physical copies to the teacher.',
                        'is_active' => true,
                    ]);
                }
            }
        }
        
        $this->command->info('Assignment seeder completed successfully!');
    }
}