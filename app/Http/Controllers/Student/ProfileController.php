<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }
        
        $student->load(['school', 'classModel', 'user', 'subjects']);
        
        // Get comprehensive student statistics
        $stats = [
            'academic_info' => $student->getAcademicInfo(),
            'attendance_stats' => $student->getAttendanceStatistics(),
            'grade_stats' => $student->getGradeStatistics(),
            'academic_status' => $student->getAcademicStatus(),
            'emergency_contact' => $student->getEmergencyContactInfo(),
        ];

        // Get recent activity
        $recentActivity = [
            'recent_grades' => $student->getRecentGrades(5),
            'recent_attendance' => $student->getRecentAttendance(10),
        ];

        // Get academic achievements
        $achievements = $this->getAcademicAchievements($student);

        return view('student.profile.show', compact('student', 'stats', 'recentActivity', 'achievements'));
    }

    public function edit()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }
        
        $student->load(['school', 'classModel', 'user']);
        
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update user information
        $userData = [
            'name' => trim($request->first_name . ' ' . $request->last_name),
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update student information
        $studentData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $studentData['photo'] = $request->file('photo')->store('student-photos', 'public');
        }

        $student->update($studentData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $student->load(['school', 'classModel', 'user'])
            ]);
        }

        return redirect()->route('student.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    /**
     * Display academic records and transcript
     */
    public function academicRecords()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Get comprehensive academic records
        $academicRecords = [
            'transcript' => $this->getTranscript($student),
            'achievements' => $this->getAcademicAchievements($student),
            'attendance_history' => $this->getAttendanceHistory($student),
            'grade_history' => $this->getGradeHistory($student),
            'subject_performance' => $this->getSubjectPerformance($student),
        ];

        return view('student.profile.academic-records', compact('student', 'academicRecords'));
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'assignment_reminders' => 'boolean',
            'grade_notifications' => 'boolean',
            'attendance_alerts' => 'boolean',
            'dashboard_theme' => 'in:light,dark,auto',
            'language' => 'in:en,es,fr,de',
        ]);

        // Update user preferences (stored in JSON field)
        $preferences = $user->preferences ?? [];
        $preferences['notifications'] = [
            'email' => $request->boolean('email_notifications'),
            'sms' => $request->boolean('sms_notifications'),
            'assignment_reminders' => $request->boolean('assignment_reminders'),
            'grade_notifications' => $request->boolean('grade_notifications'),
            'attendance_alerts' => $request->boolean('attendance_alerts'),
        ];
        $preferences['dashboard_theme'] = $request->dashboard_theme ?? 'light';
        $preferences['language'] = $request->language ?? 'en';

        $user->update(['preferences' => $preferences]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Preferences updated successfully.');
    }

    /**
     * Get academic achievements
     */
    private function getAcademicAchievements($student)
    {
        $achievements = [];
        
        // Get high grades (90% and above)
        $excellentGrades = Grade::where('student_id', $student->id)
            ->whereRaw('(marks_obtained / total_marks * 100) >= 90')
            ->with('subject')
            ->orderBy('exam_date', 'desc')
            ->limit(10)
            ->get();

        foreach ($excellentGrades as $grade) {
            $achievements[] = [
                'type' => 'academic_excellence',
                'title' => 'Excellent Performance',
                'description' => "Scored {$grade->calculated_percentage}% in " . ($grade->subject->name ?? 'Unknown Subject'),
                'date' => $grade->exam_date,
                'icon' => 'fas fa-star',
                'color' => 'success'
            ];
        }

        // Perfect attendance achievements
        $attendanceStats = $student->getAttendanceStatistics();
        if ($attendanceStats['attendance_percentage'] >= 95) {
            $achievements[] = [
                'type' => 'perfect_attendance',
                'title' => 'Excellent Attendance',
                'description' => "Maintained {$attendanceStats['attendance_percentage']}% attendance rate",
                'date' => now(),
                'icon' => 'fas fa-calendar-check',
                'color' => 'primary'
            ];
        }

        // Sort by date
        usort($achievements, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($achievements, 0, 15);
    }

    /**
     * Get student transcript
     */
    private function getTranscript($student)
    {
        $grades = Grade::where('student_id', $student->id)
            ->with(['subject', 'teacher'])
            ->orderBy('exam_date', 'desc')
            ->get();

        $transcript = $grades->groupBy('subject.name')->map(function($subjectGrades) {
            $subject = $subjectGrades->first()->subject;
            return [
                'subject_name' => $subject ? $subject->name : 'Unknown Subject',
                'subject_code' => $subject ? $subject->code : 'N/A',
                'total_assessments' => $subjectGrades->count(),
                'average_percentage' => round($subjectGrades->avg('calculated_percentage'), 1),
                'highest_grade' => $subjectGrades->max('calculated_percentage'),
                'lowest_grade' => $subjectGrades->min('calculated_percentage'),
                'latest_grade' => $subjectGrades->first()->calculated_percentage,
                'grade_letter' => $this->getLetterGrade($subjectGrades->avg('calculated_percentage')),
                'assessments' => $subjectGrades->map(function($grade) {
                    return [
                        'exam_type' => $grade->exam_type,
                        'percentage' => $grade->calculated_percentage,
                        'marks_obtained' => $grade->marks_obtained,
                        'total_marks' => $grade->total_marks,
                        'exam_date' => $grade->exam_date,
                        'teacher' => $grade->teacher->full_name ?? 'Unknown'
                    ];
                })->toArray()
            ];
        });

        return $transcript->values()->all();
    }

    /**
     * Get attendance history
     */
    private function getAttendanceHistory($student)
    {
        $attendance = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(100)
            ->get();

        $monthlyStats = $attendance->groupBy(function($record) {
            return Carbon::parse($record->date)->format('Y-m');
        })->map(function($monthRecords) {
            $total = $monthRecords->count();
            $present = $monthRecords->where('status', 'present')->count();
            $absent = $monthRecords->where('status', 'absent')->count();
            $late = $monthRecords->where('status', 'late')->count();
            
            return [
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0
            ];
        });

        return $monthlyStats->toArray();
    }

    /**
     * Get grade history by subject
     */
    private function getGradeHistory($student)
    {
        return Grade::where('student_id', $student->id)
            ->with(['subject', 'teacher'])
            ->orderBy('exam_date', 'desc')
            ->get()
            ->map(function($grade) {
                return [
                    'subject' => $grade->subject->name ?? 'Unknown',
                    'exam_type' => $grade->exam_type,
                    'percentage' => $grade->calculated_percentage,
                    'marks_obtained' => $grade->marks_obtained,
                    'total_marks' => $grade->total_marks,
                    'exam_date' => $grade->exam_date,
                    'teacher' => $grade->teacher->full_name ?? 'Unknown',
                    'grade_letter' => $this->getLetterGrade($grade->calculated_percentage)
                ];
            })
            ->toArray();
    }

    /**
     * Get subject performance analysis
     */
    private function getSubjectPerformance($student)
    {
        $grades = Grade::where('student_id', $student->id)
            ->with('subject')
            ->get();

        return $grades->groupBy('subject_id')->map(function($subjectGrades) {
            $subject = $subjectGrades->first()->subject;
            $averagePercentage = $subjectGrades->avg('calculated_percentage');
            
            return [
                'subject_name' => $subject ? $subject->name : 'Unknown',
                'total_assessments' => $subjectGrades->count(),
                'average_percentage' => round($averagePercentage, 1),
                'grade_letter' => $this->getLetterGrade($averagePercentage),
                'performance_level' => $this->getPerformanceLevel($averagePercentage),
                'trend' => $this->calculateTrend($subjectGrades),
                'strengths' => $averagePercentage >= 80,
                'needs_improvement' => $averagePercentage < 70
            ];
        })->values()->all();
    }

    /**
     * Get letter grade from percentage
     */
    private function getLetterGrade($percentage)
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    /**
     * Get performance level
     */
    private function getPerformanceLevel($percentage)
    {
        if ($percentage >= 90) return 'Excellent';
        if ($percentage >= 80) return 'Good';
        if ($percentage >= 70) return 'Satisfactory';
        if ($percentage >= 60) return 'Needs Improvement';
        return 'Poor';
    }

    /**
     * Calculate grade trend
     */
    private function calculateTrend($grades)
    {
        if ($grades->count() < 2) return 'stable';
        
        $recent = $grades->sortByDesc('exam_date')->take(3)->avg('calculated_percentage');
        $older = $grades->sortByDesc('exam_date')->skip(3)->take(3)->avg('calculated_percentage');
        
        if ($recent > $older + 5) return 'improving';
        if ($recent < $older - 5) return 'declining';
        return 'stable';
    }
}
