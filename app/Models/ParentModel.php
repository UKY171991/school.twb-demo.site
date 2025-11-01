<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'school_id',
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'occupation',
        'company',
        'annual_income',
        'photo',
        'relationship',
        'is_primary_contact',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'annual_income' => 'decimal:2',
        'is_primary_contact' => 'boolean',
    ];

    protected $appends = [
        'full_name',
        'age',
        'photo_url',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function children()
    {
        return $this->students();
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        $name = trim($this->first_name . ' ' . $this->last_name);
        return $name ?: 'Unknown Parent';
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        return asset('vendor/adminlte/dist/img/user2-160x160.jpg');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name . ' (' . ucfirst($this->relationship) . ')';
    }

    public function getRelationshipBadgeAttribute(): string
    {
        return match($this->relationship) {
            'father' => '<span class="badge badge-primary">Father</span>',
            'mother' => '<span class="badge badge-info">Mother</span>',
            'guardian' => '<span class="badge badge-secondary">Guardian</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    /**
     * Get parent's contact information
     */
    public function getContactInfo(): array
    {
        return [
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'is_primary_contact' => $this->is_primary_contact,
            'relationship' => $this->relationship,
        ];
    }

    /**
     * Get professional information
     */
    public function getProfessionalInfo(): array
    {
        return [
            'occupation' => $this->occupation,
            'company' => $this->company,
            'annual_income' => $this->annual_income,
        ];
    }

    /**
     * Get children's academic summary
     */
    public function getChildrenAcademicSummary(): array
    {
        $children = $this->children()->with(['classModel', 'grades', 'attendance'])->get();
        
        return $children->map(function($child) {
            $attendanceStats = $child->getAttendanceStatistics();
            $gradeStats = $child->getGradeStatistics();
            
            return [
                'student_id' => $child->student_id,
                'name' => $child->full_name,
                'class' => $child->classModel?->name,
                'status' => $child->status,
                'attendance_percentage' => $attendanceStats['attendance_percentage'],
                'average_grade' => $gradeStats['average_grade'],
                'total_subjects' => $gradeStats['total_grades'] > 0 ? count($gradeStats['grade_distribution']) : 0,
                'needs_attention' => $child->getAcademicStatus()['needs_attention'],
            ];
        })->toArray();
    }

    /**
     * Get overall family academic performance
     */
    public function getFamilyAcademicPerformance(): array
    {
        $children = $this->children()->get();
        
        if ($children->isEmpty()) {
            return [
                'total_children' => 0,
                'average_attendance' => 0,
                'average_grades' => 0,
                'children_needing_attention' => 0,
                'overall_status' => 'No Data',
            ];
        }

        $totalAttendance = 0;
        $totalGrades = 0;
        $childrenNeedingAttention = 0;
        $childrenWithData = 0;

        foreach ($children as $child) {
            $attendanceStats = $child->getAttendanceStatistics();
            $gradeStats = $child->getGradeStatistics();
            $academicStatus = $child->getAcademicStatus();

            if ($attendanceStats['total_days'] > 0) {
                $totalAttendance += $attendanceStats['attendance_percentage'];
                $childrenWithData++;
            }

            if ($gradeStats['total_grades'] > 0) {
                $totalGrades += $gradeStats['average_grade'];
            }

            if ($academicStatus['needs_attention']) {
                $childrenNeedingAttention++;
            }
        }

        $avgAttendance = $childrenWithData > 0 ? $totalAttendance / $childrenWithData : 0;
        $avgGrades = $childrenWithData > 0 ? $totalGrades / $childrenWithData : 0;

        return [
            'total_children' => $children->count(),
            'average_attendance' => round($avgAttendance, 2),
            'average_grades' => round($avgGrades, 2),
            'children_needing_attention' => $childrenNeedingAttention,
            'overall_status' => $this->calculateOverallFamilyStatus($avgAttendance, $avgGrades, $childrenNeedingAttention, $children->count()),
        ];
    }

    /**
     * Calculate overall family academic status
     */
    private function calculateOverallFamilyStatus(float $avgAttendance, float $avgGrades, int $needingAttention, int $totalChildren): string
    {
        if ($totalChildren === 0) {
            return 'No Data';
        }

        $attentionRatio = $needingAttention / $totalChildren;
        
        if ($attentionRatio > 0.5) {
            return 'Needs Attention';
        }

        $overallScore = ($avgAttendance + $avgGrades) / 2;
        
        return match(true) {
            $overallScore >= 85 => 'Excellent',
            $overallScore >= 75 => 'Good',
            $overallScore >= 65 => 'Average',
            default => 'Below Average'
        };
    }

    /**
     * Get recent activities for all children
     */
    public function getRecentChildrenActivities(int $days = 30): array
    {
        $activities = [];
        
        foreach ($this->children as $child) {
            // Recent grades
            $recentGrades = $child->grades()
                                 ->with('subject')
                                 ->where('created_at', '>=', Carbon::now()->subDays($days))
                                 ->orderBy('created_at', 'desc')
                                 ->get();

            foreach ($recentGrades as $grade) {
                $activities[] = [
                    'type' => 'grade',
                    'child_name' => $child->full_name,
                    'description' => "Grade received in {$grade->subject->name}: {$grade->marks_obtained}/{$grade->total_marks}",
                    'date' => $grade->created_at,
                    'status' => $grade->marks_obtained >= ($grade->total_marks * 0.6) ? 'positive' : 'negative',
                ];
            }

            // Recent attendance issues
            $recentAbsences = $child->attendance()
                                   ->where('status', 'absent')
                                   ->where('date', '>=', Carbon::now()->subDays($days))
                                   ->orderBy('date', 'desc')
                                   ->get();

            foreach ($recentAbsences as $absence) {
                $activities[] = [
                    'type' => 'attendance',
                    'child_name' => $child->full_name,
                    'description' => "Absent on {$absence->date->format('M d, Y')}",
                    'date' => $absence->created_at,
                    'status' => 'negative',
                ];
            }
        }

        // Sort by date and return latest activities
        usort($activities, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($activities, 0, 20);
    }

    /**
     * Get communication preferences
     */
    public function getCommunicationPreferences(): array
    {
        // This would be stored in user preferences or separate table
        // For now, return defaults based on contact info
        return [
            'preferred_method' => $this->email ? 'email' : 'phone',
            'email_notifications' => !empty($this->email),
            'sms_notifications' => !empty($this->phone),
            'emergency_contact' => $this->is_primary_contact,
            'language_preference' => 'en', // Default to English
        ];
    }

    /**
     * Check if parent is primary contact for any child
     */
    public function isPrimaryContact(): bool
    {
        return $this->is_primary_contact;
    }

    /**
     * Get emergency contact information
     */
    public function getEmergencyContactInfo(): array
    {
        return [
            'name' => $this->full_name,
            'relationship' => $this->relationship,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'is_primary' => $this->is_primary_contact,
        ];
    }

    /**
     * Scopes
     */
    public function scopePrimaryContacts($query)
    {
        return $query->where('is_primary_contact', true);
    }

    public function scopeByRelationship($query, string $relationship)
    {
        return $query->where('relationship', $relationship);
    }

    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeWithChildren($query)
    {
        return $query->has('students');
    }

    public function scopeByIncomeRange($query, float $minIncome, float $maxIncome)
    {
        return $query->whereBetween('annual_income', [$minIncome, $maxIncome]);
    }
}
