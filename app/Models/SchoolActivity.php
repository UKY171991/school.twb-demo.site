<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'description',
        'activity_type',
        'activity_date',
        'start_time',
        'end_time',
        'location',
        'organizer_id',
        'max_participants',
        'requires_permission',
        'requires_payment',
        'payment_amount',
        'payment_deadline',
        'permission_deadline',
        'medical_form_required',
        'transport_provided',
        'pickup_location',
        'return_location',
        'contact_person',
        'contact_phone',
        'contact_email',
        'special_instructions',
        'is_active'
    ];

    protected $casts = [
        'activity_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'payment_deadline' => 'datetime',
        'permission_deadline' => 'datetime',
        'requires_permission' => 'boolean',
        'requires_payment' => 'boolean',
        'medical_form_required' => 'boolean',
        'transport_provided' => 'boolean',
        'is_active' => 'boolean',
        'payment_amount' => 'decimal:2',
        'max_participants' => 'integer'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function permissions()
    {
        return $this->hasMany(StudentPermission::class, 'activity_id');
    }

    public function participants()
    {
        return $this->belongsToMany(Student::class, 'student_permissions', 'activity_id', 'student_id')
                   ->wherePivot('status', 'approved');
    }

    /**
     * Get activity type badge
     */
    public function getTypeBadgeAttribute(): string
    {
        return match($this->activity_type) {
            'field_trip' => '<span class="badge badge-primary">Field Trip</span>',
            'sports_event' => '<span class="badge badge-info">Sports Event</span>',
            'school_event' => '<span class="badge badge-success">School Event</span>',
            'fundraiser' => '<span class="badge badge-warning">Fundraiser</span>',
            'academic' => '<span class="badge badge-secondary">Academic</span>',
            'cultural' => '<span class="badge badge-dark">Cultural</span>',
            default => '<span class="badge badge-light">' . ucfirst(str_replace('_', ' ', $this->activity_type)) . '</span>'
        };
    }

    /**
     * Get formatted activity date and time
     */
    public function getFormattedDateTimeAttribute(): string
    {
        $date = $this->activity_date->format('M d, Y');
        $startTime = $this->start_time?->format('g:i A');
        $endTime = $this->end_time?->format('g:i A');
        
        if ($startTime && $endTime) {
            return "$date from $startTime to $endTime";
        } elseif ($startTime) {
            return "$date at $startTime";
        }
        
        return $date;
    }

    /**
     * Get participants count
     */
    public function getParticipantsCountAttribute(): int
    {
        return $this->participants()->count();
    }

    /**
     * Get pending permissions count
     */
    public function getPendingPermissionsCountAttribute(): int
    {
        return $this->permissions()->where('status', 'pending')->count();
    }

    /**
     * Check if activity is full
     */
    public function getIsFullAttribute(): bool
    {
        return $this->max_participants && 
               $this->participants_count >= $this->max_participants;
    }

    /**
     * Check if permission deadline has passed
     */
    public function getPermissionDeadlinePassedAttribute(): bool
    {
        return $this->permission_deadline && $this->permission_deadline->isPast();
    }

    /**
     * Check if payment deadline has passed
     */
    public function getPaymentDeadlinePassedAttribute(): bool
    {
        return $this->payment_deadline && $this->payment_deadline->isPast();
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('activity_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('activity_date', '<', now()->toDateString());
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }

    public function scopeRequiringPermission($query)
    {
        return $query->where('requires_permission', true);
    }

    public function scopeRequiringPayment($query)
    {
        return $query->where('requires_payment', true);
    }

    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeOpenForRegistration($query)
    {
        return $query->where('is_active', true)
                    ->where('activity_date', '>=', now()->toDateString())
                    ->where(function($q) {
                        $q->whereNull('permission_deadline')
                          ->orWhere('permission_deadline', '>', now());
                    });
    }
}