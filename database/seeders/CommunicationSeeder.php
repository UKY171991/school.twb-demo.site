<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\Announcement;
use App\Models\Feedback;
use App\Models\Conversation;
use App\Models\Meeting;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use Carbon\Carbon;

class CommunicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        
        foreach ($schools as $school) {
            $this->seedMessages($school);
            $this->seedAnnouncements($school);
            $this->seedFeedback($school);
            $this->seedConversations($school);
            $this->seedMeetings($school);
        }
        
        $this->command->info('Communication seeder completed successfully!');
    }

    private function seedMessages(School $school)
    {
        $students = Student::where('school_id', $school->id)->with('user')->get();
        $teachers = Teacher::where('school_id', $school->id)->with('user')->get();
        
        if ($students->isEmpty() || $teachers->isEmpty()) {
            return;
        }

        // Create messages between students and teachers
        foreach ($students->take(5) as $student) {
            $teacher = $teachers->random();
            
            // Student to teacher message
            $message = Message::create([
                'school_id' => $school->id,
                'sender_id' => $student->user->id,
                'receiver_id' => $teacher->user->id,
                'subject' => $this->getRandomSubject(),
                'message' => $this->getRandomMessage(),
                'priority' => ['normal', 'normal', 'high', 'urgent'][rand(0, 3)],
                'status' => 'sent',
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);

            // Sometimes add a reply
            if (rand(0, 1)) {
                Message::create([
                    'school_id' => $school->id,
                    'sender_id' => $teacher->user->id,
                    'receiver_id' => $student->user->id,
                    'subject' => 'Re: ' . $message->subject,
                    'message' => $this->getRandomReply(),
                    'priority' => $message->priority,
                    'parent_message_id' => $message->id,
                    'status' => rand(0, 1) ? 'read' : 'sent',
                    'read_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 5)) : null,
                    'created_at' => $message->created_at->addHours(rand(1, 24)),
                ]);
            }
        }
    }

    private function seedAnnouncements(School $school)
    {
        $admins = User::where('school_id', $school->id)
            ->where('user_type', 'admin')
            ->get();
        
        if ($admins->isEmpty()) {
            return;
        }

        $announcements = [
            [
                'title' => 'Welcome to New Academic Year',
                'content' => 'We are excited to welcome all students to the new academic year. Please review your class schedules and prepare for an amazing year of learning.',
                'type' => 'academic',
                'priority' => 'high',
                'target_audience' => 'students',
            ],
            [
                'title' => 'School Sports Day',
                'content' => 'Our annual sports day will be held next month. All students are encouraged to participate in various sporting events.',
                'type' => 'event',
                'priority' => 'normal',
                'target_audience' => 'all',
            ],
            [
                'title' => 'Library Hours Extended',
                'content' => 'The school library will now be open until 8 PM on weekdays to support student learning.',
                'type' => 'general',
                'priority' => 'normal',
                'target_audience' => 'students',
            ],
            [
                'title' => 'Parent-Teacher Conference',
                'content' => 'Parent-teacher conferences are scheduled for next week. Please check your email for appointment details.',
                'type' => 'academic',
                'priority' => 'high',
                'target_audience' => 'parents',
            ],
            [
                'title' => 'System Maintenance Notice',
                'content' => 'The school management system will undergo maintenance this weekend. Some services may be temporarily unavailable.',
                'type' => 'maintenance',
                'priority' => 'urgent',
                'target_audience' => 'all',
            ],
        ];

        foreach ($announcements as $announcementData) {
            Announcement::create([
                'school_id' => $school->id,
                'author_id' => $admins->random()->id,
                'title' => $announcementData['title'],
                'content' => $announcementData['content'],
                'type' => $announcementData['type'],
                'priority' => $announcementData['priority'],
                'target_audience' => $announcementData['target_audience'],
                'is_published' => true,
                'is_pinned' => rand(0, 1),
                'published_at' => Carbon::now()->subDays(rand(1, 15)),
                'expires_at' => rand(0, 1) ? Carbon::now()->addDays(rand(30, 90)) : null,
            ]);
        }
    }

    private function seedFeedback(School $school)
    {
        $students = Student::where('school_id', $school->id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        $teachers = Teacher::where('school_id', $school->id)->get();
        
        if ($students->isEmpty()) {
            return;
        }

        $feedbackTypes = ['course_evaluation', 'teacher_feedback', 'suggestion', 'complaint', 'general'];
        $feedbackTitles = [
            'course_evaluation' => [
                'Course Content Review',
                'Curriculum Feedback',
                'Learning Materials Assessment',
            ],
            'teacher_feedback' => [
                'Teaching Method Feedback',
                'Classroom Experience',
                'Teacher Performance Review',
            ],
            'suggestion' => [
                'Improvement Suggestion',
                'New Activity Proposal',
                'Facility Enhancement Idea',
            ],
            'complaint' => [
                'Classroom Issue',
                'Facility Problem',
                'Service Complaint',
            ],
            'general' => [
                'General Feedback',
                'School Experience',
                'Overall Comments',
            ],
        ];

        foreach ($students->take(10) as $student) {
            $type = $feedbackTypes[array_rand($feedbackTypes)];
            $titles = $feedbackTitles[$type];
            
            Feedback::create([
                'school_id' => $school->id,
                'student_id' => $student->id,
                'subject_id' => $subjects->isNotEmpty() ? $subjects->random()->id : null,
                'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->id : null,
                'type' => $type,
                'title' => $titles[array_rand($titles)],
                'content' => $this->getRandomFeedbackContent($type),
                'rating' => rand(1, 5),
                'status' => ['submitted', 'under_review', 'responded', 'resolved'][rand(0, 3)],
                'is_anonymous' => rand(0, 1),
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);
        }
    }

    private function getRandomSubject(): string
    {
        $subjects = [
            'Question about homework assignment',
            'Request for extra help',
            'Clarification needed on exam',
            'Project submission inquiry',
            'Schedule change request',
            'Academic guidance needed',
            'Course material question',
            'Assignment deadline extension',
            'Study group formation',
            'Grade inquiry',
        ];

        return $subjects[array_rand($subjects)];
    }

    private function getRandomMessage(): string
    {
        $messages = [
            'I hope this message finds you well. I wanted to reach out regarding the recent assignment and would appreciate your guidance on how to proceed.',
            'Thank you for your time in class today. I have a few questions about the material we covered and would like to schedule a meeting if possible.',
            'I am writing to request clarification on the upcoming exam format. Could you please provide more details about what topics will be covered?',
            'I wanted to inform you that I may need an extension on the current project due to some personal circumstances. I would appreciate your understanding.',
            'Could you please help me understand the concept we discussed in class? I am having some difficulty grasping the material.',
            'I would like to thank you for your excellent teaching. Your explanations have really helped me improve my understanding of the subject.',
            'I am interested in participating in additional learning opportunities. Are there any extra credit assignments or study groups available?',
            'I wanted to discuss my recent test performance and see if there are ways I can improve my understanding of the material.',
        ];

        return $messages[array_rand($messages)];
    }

    private function getRandomReply(): string
    {
        $replies = [
            'Thank you for reaching out. I appreciate your proactive approach to learning. Let me address your concerns.',
            'I am glad you asked for clarification. This shows your commitment to understanding the material thoroughly.',
            'I understand your situation and appreciate you communicating with me. Let us work together to find a solution.',
            'Your question is very thoughtful. I would be happy to provide additional explanation during office hours.',
            'Thank you for your feedback. I am always looking for ways to improve my teaching methods.',
            'I am pleased to see your enthusiasm for the subject. Keep up the excellent work!',
            'Your request is reasonable. Please see me after class so we can discuss this further.',
            'I appreciate your honesty about the challenges you are facing. Let us schedule a time to work through this together.',
        ];

        return $replies[array_rand($replies)];
    }

    private function getRandomFeedbackContent(string $type): string
    {
        $content = [
            'course_evaluation' => [
                'The course content is well-structured and engaging. I particularly enjoyed the practical examples provided during lectures.',
                'I found the course materials to be comprehensive, though some topics could benefit from additional explanation.',
                'The pace of the course is appropriate, and the assignments help reinforce the concepts learned in class.',
            ],
            'teacher_feedback' => [
                'The teacher explains concepts clearly and is always willing to help students who are struggling.',
                'I appreciate the interactive teaching style and the way complex topics are broken down into manageable parts.',
                'The teacher creates a positive learning environment and encourages student participation.',
            ],
            'suggestion' => [
                'I suggest adding more hands-on activities to make the learning experience more engaging.',
                'It would be helpful to have more practice problems available for students to work on independently.',
                'Consider organizing study groups to help students collaborate and learn from each other.',
            ],
            'complaint' => [
                'I have concerns about the classroom temperature, which sometimes makes it difficult to concentrate.',
                'The noise level in the hallways during class time can be distracting.',
                'Some of the equipment in the lab needs maintenance or replacement.',
            ],
            'general' => [
                'Overall, I am satisfied with my learning experience and feel well-prepared for future challenges.',
                'The school provides a supportive environment for learning and personal growth.',
                'I appreciate the dedication of the teaching staff and their commitment to student success.',
            ],
        ];

        $typeContent = $content[$type] ?? $content['general'];
        return $typeContent[array_rand($typeContent)];
    }

    private function seedConversations(School $school)
    {
        $parents = User::where('school_id', $school->id)
            ->where('user_type', 'parent')
            ->get();
        
        $teachers = User::where('school_id', $school->id)
            ->where('user_type', 'teacher')
            ->get();
        
        $students = Student::where('school_id', $school->id)->get();
        
        if ($parents->isEmpty() || $teachers->isEmpty() || $students->isEmpty()) {
            return;
        }

        foreach ($parents->take(3) as $parent) {
            $children = $parent->children;
            if ($children->isEmpty()) continue;
            
            foreach ($children->take(2) as $child) {
                $teacher = $teachers->random();
                
                // Create conversation
                $conversation = Conversation::create([
                    'parent_id' => $parent->id,
                    'teacher_id' => $teacher->id,
                    'student_id' => $child->id,
                    'subject' => $this->getRandomConversationSubject(),
                    'status' => 'active',
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);

                // Add messages to conversation
                $messageCount = rand(2, 6);
                for ($i = 0; $i < $messageCount; $i++) {
                    $isParentSender = $i % 2 === 0; // Alternate between parent and teacher
                    
                    Message::create([
                        'school_id' => $school->id,
                        'conversation_id' => $conversation->id,
                        'sender_id' => $isParentSender ? $parent->id : $teacher->id,
                        'receiver_id' => $isParentSender ? $teacher->id : $parent->id,
                        'subject' => $i === 0 ? $conversation->subject : 'Re: ' . $conversation->subject,
                        'message' => $this->getRandomConversationMessage($isParentSender),
                        'is_read' => rand(0, 1),
                        'created_at' => $conversation->created_at->addHours($i * rand(2, 24)),
                    ]);
                }
                
                $conversation->touch(); // Update conversation timestamp
            }
        }
    }

    private function seedMeetings(School $school)
    {
        $parents = User::where('school_id', $school->id)
            ->where('user_type', 'parent')
            ->get();
        
        $teachers = User::where('school_id', $school->id)
            ->where('user_type', 'teacher')
            ->get();
        
        if ($parents->isEmpty() || $teachers->isEmpty()) {
            return;
        }

        foreach ($parents->take(5) as $parent) {
            $children = $parent->children;
            if ($children->isEmpty()) continue;
            
            $child = $children->random();
            $teacher = $teachers->random();
            
            $status = ['pending', 'confirmed', 'completed', 'cancelled'][rand(0, 3)];
            $requestedDate = Carbon::now()->subDays(rand(1, 15));
            
            $meeting = Meeting::create([
                'parent_id' => $parent->id,
                'teacher_id' => $teacher->id,
                'student_id' => $child->id,
                'requested_at' => $requestedDate,
                'preferred_date' => $requestedDate->copy()->addDays(rand(3, 10)),
                'preferred_time' => sprintf('%02d:%02d', rand(9, 16), [0, 30][rand(0, 1)]),
                'purpose' => $this->getRandomMeetingPurpose(),
                'meeting_type' => ['in_person', 'video_call', 'phone_call'][rand(0, 2)],
                'status' => $status,
                'scheduled_at' => $status === 'confirmed' || $status === 'completed' ? 
                    $requestedDate->copy()->addDays(rand(5, 12)) : null,
                'agenda' => rand(0, 1) ? $this->getRandomMeetingAgenda() : null,
                'notes' => $status === 'completed' ? $this->getRandomMeetingNotes() : null,
                'follow_up_required' => $status === 'completed' ? rand(0, 1) : false,
                'created_at' => $requestedDate,
            ]);
        }
    }

    private function getRandomConversationSubject(): string
    {
        $subjects = [
            'Academic Progress Discussion',
            'Homework Concerns',
            'Behavioral Update',
            'Class Participation',
            'Assignment Help Request',
            'Parent-Teacher Conference Follow-up',
            'Study Habits Improvement',
            'Exam Preparation',
            'Extracurricular Activities',
            'Academic Goals Setting',
        ];

        return $subjects[array_rand($subjects)];
    }

    private function getRandomConversationMessage(bool $isParent): string
    {
        if ($isParent) {
            $messages = [
                'Hello, I wanted to discuss my child\'s recent performance in your class.',
                'Thank you for your time. I have some concerns about the homework assignments.',
                'I appreciate your feedback on my child\'s progress. Could we schedule a meeting?',
                'I noticed some changes in my child\'s attitude towards the subject. Any insights?',
                'Could you please provide some guidance on how to help my child at home?',
            ];
        } else {
            $messages = [
                'Thank you for reaching out. I\'m happy to discuss your child\'s progress.',
                'Your child is doing well overall. Here are some areas we can focus on.',
                'I appreciate your involvement in your child\'s education. Let me share some observations.',
                'I\'d be glad to provide some strategies to help support learning at home.',
                'Your child shows great potential. With some additional support, they can excel.',
            ];
        }

        return $messages[array_rand($messages)];
    }

    private function getRandomMeetingPurpose(): string
    {
        $purposes = [
            'Discuss academic progress and areas for improvement',
            'Address behavioral concerns and develop strategies',
            'Review homework completion and study habits',
            'Plan for upcoming exams and assessments',
            'Discuss extracurricular participation opportunities',
            'Address social interactions and peer relationships',
            'Review IEP goals and accommodations needed',
            'Discuss college preparation and career planning',
            'Address attendance concerns and solutions',
            'Celebrate achievements and set new goals',
        ];

        return $purposes[array_rand($purposes)];
    }

    private function getRandomMeetingAgenda(): string
    {
        $agendas = [
            '1. Review current grades\n2. Discuss study strategies\n3. Set goals for next quarter',
            '1. Address homework concerns\n2. Develop home support plan\n3. Schedule follow-up',
            '1. Celebrate recent improvements\n2. Identify continued challenges\n3. Plan next steps',
            '1. Review test performance\n2. Discuss learning style\n3. Explore additional resources',
        ];

        return $agendas[array_rand($agendas)];
    }

    private function getRandomMeetingNotes(): string
    {
        $notes = [
            'Productive meeting. Parent committed to supporting homework routine. Student shows improvement in class participation.',
            'Discussed strategies for better organization. Will implement daily planner system. Follow-up in 2 weeks.',
            'Addressed concerns about math concepts. Recommended tutoring resources. Parent will monitor progress.',
            'Celebrated student\'s recent achievements. Set goals for next semester. Parent very supportive.',
        ];

        return $notes[array_rand($notes)];
    }
}