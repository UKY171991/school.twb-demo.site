<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'family_name',
        'primary_contact_name',
        'primary_contact_phone',
        'primary_contact_email',
        'secondary_contact_name',
        'secondary_contact_phone',
        'secondary_contact_email',
        'home_address',
        'work_address',
        'medical_information',
        'special_instructions',
        'notification_preferences',
        'communication_preferences',
        'privacy_settings'
    ];

    protected $casts = [
        'notification_preferences' => 'array',
        'communication_preferences' => 'array',
        'privacy_settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class, 'parent_id', 'parent_id');
    }

    /**
     * Get notification preference for a specific type
     */
    public function getNotificationPreference(string $type, bool $default = true): bool
    {
        return data_get($this->notification_preferences, $type, $default);
    }

    /**
     * Set notification preference for a specific type
     */
    public function setNotificationPreference(string $type, bool $value): void
    {
        $preferences = $this->notification_preferences ?? [];
        $preferences[$type] = $value;
        $this->update(['notification_preferences' => $preferences]);
    }

    /**
     * Get communication preference for a specific type
     */
    public function getCommunicationPreference(string $type, string $default = 'email'): string
    {
        return data_get($this->communication_preferences, $type, $default);
    }

    /**
     * Set communication preference for a specific type
     */
    public function setCommunicationPreference(string $type, string $value): void
    {
        $preferences = $this->communication_preferences ?? [];
        $preferences[$type] = $value;
        $this->update(['communication_preferences' => $preferences]);
    }

    /**
     * Get privacy setting for a specific type
     */
    public function getPrivacySetting(string $type, bool $default = false): bool
    {
        return data_get($this->privacy_settings, $type, $default);
    }

    /**
     * Set privacy setting for a specific type
     */
    public function setPrivacySetting(string $type, bool $value): void
    {
        $settings = $this->privacy_settings ?? [];
        $settings[$type] = $value;
        $this->update(['privacy_settings' => $settings]);
    }

    /**
     * Get default notification preferences
     */
    public static function getDefaultNotificationPreferences(): array
    {
        return [
            'grade_updates' => true,
            'attendance_alerts' => true,
            'assignment_reminders' => true,
            'meeting_notifications' => true,
            'school_announcements' => true,
            'emergency_alerts' => true,
            'behavior_reports' => true,
            'payment_reminders' => true,
            'event_notifications' => true,
            'schedule_changes' => true
        ];
    }

    /**
     * Get default communication preferences
     */
    public static function getDefaultCommunicationPreferences(): array
    {
        return [
            'grade_updates' => 'email',
            'attendance_alerts' => 'sms',
            'assignment_reminders' => 'email',
            'meeting_notifications' => 'both',
            'school_announcements' => 'email',
            'emergency_alerts' => 'both',
            'behavior_reports' => 'email',
            'payment_reminders' => 'email',
            'event_notifications' => 'email',
            'schedule_changes' => 'both'
        ];
    }

    /**
     * Get default privacy settings
     */
    public static function getDefaultPrivacySettings(): array
    {
        return [
            'share_contact_info' => false,
            'allow_photo_sharing' => true,
            'directory_listing' => false,
            'volunteer_contact' => false,
            'marketing_communications' => false,
            'third_party_sharing' => false,
            'academic_info_sharing' => false,
            'emergency_contact_sharing' => true
        ];
    }

    /**
     * Initialize default preferences
     */
    public function initializeDefaults(): void
    {
        if (empty($this->notification_preferences)) {
            $this->notification_preferences = self::getDefaultNotificationPreferences();
        }
        
        if (empty($this->communication_preferences)) {
            $this->communication_preferences = self::getDefaultCommunicationPreferences();
        }
        
        if (empty($this->privacy_settings)) {
            $this->privacy_settings = self::getDefaultPrivacySettings();
        }
        
        $this->save();
    }
}