<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'activity_id',
        'permission_type',
        'title',
        'description',
        'activity_date',
        'deadline',
        'status',
        'parent_notes',
        'teacher_notes',
        'requested_at',
        'responded_at',
        'requires_payment',
        'payment_amount',
        'payment_deadline',
        'medical_form_required',
        'transport_required',
        'pickup_time',
        'return_time',
        'location'
    ];

    protected $casts = [
        'activity_date' => 'date',
        'deadline' => 'datetime',
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
        'payment_deadline' => 'datetime',
        'pickup_time' => 'datetime',
        'return_time' => 'datetime',
        'requires_payment' => 'boolean',
        'medical_form_required' => 'boolean',
        'transport_required' => 'boolean',
        'payment_amount' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function activity()
    {
        return $this->belongsTo(SchoolActivity::class, 'activity_id');
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'approved' => '<span class="badge badge-success">Approved</span>',
            'denied' => '<span class="badge badge-danger">Denied</span>',
            'expired' => '<span class="badge badge-secondary">Expired</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    /**
     * Get permission type badge HTML
     */
    public function getTypeBadgeAttribute(): string
    {
        return match($this->permission_type) {
            'field_trip' => '<span class="badge badge-primary">Field Trip</span>',
            'sports_event' => '<span class="badge badge-info">Sports Event</span>',
            'school_event' => '<span class="badge badge-success">School Event</span>',
            'medical' => '<span class="badge badge-warning">Medical</span>',
            'transport' => '<span class="badge badge-secondary">Transport</span>',
            'after_school' => '<span class="badge badge-dark">After School</span>',
            default => '<span class="badge badge-light">' . ucfirst(str_replace('_', ' ', $this->permission_type)) . '</span>'
        };
    }

    /**
     * Check if permission is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->deadline && $this->deadline->isPast();
    }

    /**
     * Check if permission requires urgent attention
     */
    public function getIsUrgentAttribute(): bool
    {
        return $this->status === 'pending' && 
               $this->deadline && 
               $this->deadline->diffInDays(now()) <= 2;
    }

    /**
     * Get days until deadline
     */
    public function getDaysUntilDeadlineAttribute(): ?int
    {
        if (!$this->deadline) {
            return null;
        }
        
        return max(0, now()->diffInDays($this->deadline, false));
    }

    /**
     * Get formatted activity date
     */
    public function getFormattedActivityDateAttribute(): ?string
    {
        return $this->activity_date?->format('M d, Y');
    }

    /**
     * Get formatted deadline
     */
    public function getFormattedDeadlineAttribute(): ?string
    {
        return $this->deadline?->format('M d, Y \a\t g:i A');
    }

    /**
     * Get formatted pickup time
     */
    public function getFormattedPickupTimeAttribute(): ?string
    {
        return $this->pickup_time?->format('g:i A');
    }

    /**
     * Get formatted return time
     */
    public function getFormattedReturnTimeAttribute(): ?string
    {
        return $this->return_time?->format('g:i A');
    }

    /**
     * Check if payment is required and overdue
     */
    public function getPaymentOverdueAttribute(): bool
    {
        return $this->requires_payment && 
               $this->status === 'approved' && 
               $this->payment_deadline && 
               $this->payment_deadline->isPast();
    }

    /**
     * Mark as approved
     */
    public function approve(string $parentNotes = null): void
    {
        $this->update([
            'status' => 'approved',
            'parent_notes' => $parentNotes,
            'responded_at' => now()
        ]);
    }

    /**
     * Mark as denied
     */
    public function deny(string $parentNotes = null): void
    {
        $this->update([
            'status' => 'denied',
            'parent_notes' => $parentNotes,
            'responded_at' => now()
        ]);
    }

    /**
     * Check if permission can be modified
     */
    public function canBeModified(): bool
    {
        return $this->status === 'pending' && 
               (!$this->deadline || $this->deadline->isFuture());
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDenied($query)
    {
        return $query->where('status', 'denied');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('deadline', '<', now());
    }

    public function scopeUrgent($query)
    {
        return $query->where('status', 'pending')
                    ->where('deadline', '>', now())
                    ->where('deadline', '<=', now()->addDays(2));
    }

    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('permission_type', $type);
    }

    public function scopeRequiringPayment($query)
    {
        return $query->where('requires_payment', true);
    }

    public function scopePaymentOverdue($query)
    {
        return $query->where('requires_payment', true)
                    ->where('status', 'approved')
                    ->where('payment_deadline', '<', now());
    }
}