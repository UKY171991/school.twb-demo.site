<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'subject_id',
        'teacher_id',
        'type',
        'title',
        'content',
        'rating',
        'ratings',
        'status',
        'admin_response',
        'responded_by',
        'responded_at',
        'is_anonymous',
    ];

    protected $casts = [
        'ratings' => 'array',
        'responded_at' => 'datetime',
        'is_anonymous' => 'boolean',
    ];

    protected $appends = [
        'type_badge',
        'status_badge',
        'time_ago',
        'has_response',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    // Accessors
    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'course_evaluation' => '<span class="badge badge-info">Course Evaluation</span>',
            'teacher_feedback' => '<span class="badge badge-primary">Teacher Feedback</span>',
            'suggestion' => '<span class="badge badge-success">Suggestion</span>',
            'complaint' => '<span class="badge badge-warning">Complaint</span>',
            'general' => '<span class="badge badge-secondary">General</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'submitted' => '<span class="badge badge-info">Submitted</span>',
            'under_review' => '<span class="badge badge-warning">Under Review</span>',
            'responded' => '<span class="badge badge-primary">Responded</span>',
            'resolved' => '<span class="badge badge-success">Resolved</span>',
            'closed' => '<span class="badge badge-secondary">Closed</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getHasResponseAttribute(): bool
    {
        return !is_null($this->admin_response);
    }

    /**
     * Get average rating from detailed ratings
     */
    public function getAverageRating(): float
    {
        if ($this->ratings && is_array($this->ratings)) {
            $ratings = array_values($this->ratings);
            return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0;
        }
        
        return $this->rating ?? 0;
    }

    /**
     * Get rating stars HTML
     */
    public function getRatingStars(): string
    {
        $rating = $this->rating ?? $this->getAverageRating();
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        
        return $stars;
    }

    /**
     * Scopes
     */
    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }

    public function scopeResponded($query)
    {
        return $query->whereIn('status', ['responded', 'resolved', 'closed']);
    }

    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    public function scopeWithRating($query)
    {
        return $query->whereNotNull('rating');
    }
}
