<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'relationship',
        'phone_primary',
        'phone_secondary',
        'email',
        'address',
        'is_authorized_pickup',
        'notes',
        'priority_order'
    ];

    protected $casts = [
        'is_authorized_pickup' => 'boolean',
        'priority_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function familyProfile()
    {
        return $this->belongsTo(FamilyProfile::class, 'parent_id', 'parent_id');
    }

    /**
     * Get formatted display name with relationship
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . $this->relationship . ')';
    }

    /**
     * Get primary contact method
     */
    public function getPrimaryContactAttribute(): string
    {
        return $this->phone_primary;
    }

    /**
     * Get all contact methods
     */
    public function getContactMethodsAttribute(): array
    {
        $methods = [];
        
        if ($this->phone_primary) {
            $methods[] = [
                'type' => 'phone',
                'label' => 'Primary Phone',
                'value' => $this->phone_primary
            ];
        }
        
        if ($this->phone_secondary) {
            $methods[] = [
                'type' => 'phone',
                'label' => 'Secondary Phone',
                'value' => $this->phone_secondary
            ];
        }
        
        if ($this->email) {
            $methods[] = [
                'type' => 'email',
                'label' => 'Email',
                'value' => $this->email
            ];
        }
        
        return $methods;
    }

    /**
     * Get authorization status badge
     */
    public function getAuthorizationBadgeAttribute(): string
    {
        return $this->is_authorized_pickup 
            ? '<span class="badge badge-success">Authorized Pickup</span>'
            : '<span class="badge badge-secondary">Contact Only</span>';
    }

    /**
     * Scope for authorized pickup contacts
     */
    public function scopeAuthorizedPickup($query)
    {
        return $query->where('is_authorized_pickup', true);
    }

    /**
     * Scope for contacts by priority
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority_order')->orderBy('created_at');
    }

    /**
     * Scope for parent's contacts
     */
    public function scopeForParent($query, int $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Get next priority order for a parent
     */
    public static function getNextPriorityOrder(int $parentId): int
    {
        $maxOrder = self::where('parent_id', $parentId)->max('priority_order');
        return ($maxOrder ?? 0) + 1;
    }

    /**
     * Update priority orders
     */
    public static function updatePriorityOrders(int $parentId, array $contactIds): void
    {
        foreach ($contactIds as $index => $contactId) {
            self::where('id', $contactId)
                ->where('parent_id', $parentId)
                ->update(['priority_order' => $index + 1]);
        }
    }
}