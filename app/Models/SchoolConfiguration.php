<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'key',
        'value',
        'type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Scopes
     */
    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Get typed value
     */
    public function getTypedValue()
    {
        return match($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json' => json_decode($this->value, true),
            'array' => is_string($this->value) ? json_decode($this->value, true) : $this->value,
            default => $this->value,
        };
    }

    /**
     * Set typed value
     */
    public function setTypedValue($value): bool
    {
        $storedValue = match($this->type) {
            'boolean' => $value ? '1' : '0',
            'json', 'array' => is_string($value) ? $value : json_encode($value),
            default => (string) $value,
        };

        return $this->update(['value' => $storedValue]);
    }

    /**
     * Get configuration value for school
     */
    public static function getForSchool(int $schoolId, string $key, $default = null)
    {
        $config = self::forSchool($schoolId)->byKey($key)->first();
        
        return $config ? $config->getTypedValue() : $default;
    }

    /**
     * Set configuration value for school
     */
    public static function setForSchool(int $schoolId, string $key, $value, string $type = 'string', string $description = null, bool $isPublic = false): self
    {
        $config = self::forSchool($schoolId)->byKey($key)->first();
        
        if ($config) {
            $config->setTypedValue($value);
            $config->update([
                'type' => $type,
                'description' => $description ?? $config->description,
                'is_public' => $isPublic,
            ]);
            return $config;
        }

        $storedValue = match($type) {
            'boolean' => $value ? '1' : '0',
            'json', 'array' => is_string($value) ? $value : json_encode($value),
            default => (string) $value,
        };

        return self::create([
            'school_id' => $schoolId,
            'key' => $key,
            'value' => $storedValue,
            'type' => $type,
            'description' => $description,
            'is_public' => $isPublic,
        ]);
    }

    /**
     * Get all configurations for school
     */
    public static function getAllForSchool(int $schoolId, bool $publicOnly = false): array
    {
        $query = self::forSchool($schoolId);
        
        if ($publicOnly) {
            $query->public();
        }
        
        return $query->get()->mapWithKeys(function ($config) {
            return [$config->key => $config->getTypedValue()];
        })->toArray();
    }

    /**
     * Get default configurations
     */
    public static function getDefaultConfigurations(): array
    {
        return [
            'school_year_start' => [
                'value' => '09-01',
                'type' => 'string',
                'description' => 'School year start date (MM-DD format)',
                'is_public' => true,
            ],
            'school_year_end' => [
                'value' => '06-30',
                'type' => 'string',
                'description' => 'School year end date (MM-DD format)',
                'is_public' => true,
            ],
            'grading_scale' => [
                'value' => json_encode([
                    'A' => ['min' => 90, 'max' => 100],
                    'B' => ['min' => 80, 'max' => 89],
                    'C' => ['min' => 70, 'max' => 79],
                    'D' => ['min' => 60, 'max' => 69],
                    'F' => ['min' => 0, 'max' => 59],
                ]),
                'type' => 'json',
                'description' => 'Grading scale configuration',
                'is_public' => true,
            ],
            'attendance_required_percentage' => [
                'value' => '75',
                'type' => 'integer',
                'description' => 'Minimum attendance percentage required',
                'is_public' => true,
            ],
            'max_students_per_class' => [
                'value' => '30',
                'type' => 'integer',
                'description' => 'Maximum number of students per class',
                'is_public' => false,
            ],
            'enable_parent_portal' => [
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable parent portal access',
                'is_public' => false,
            ],
            'enable_student_portal' => [
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable student portal access',
                'is_public' => false,
            ],
            'notification_email' => [
                'value' => '',
                'type' => 'string',
                'description' => 'Email address for system notifications',
                'is_public' => false,
            ],
            'school_logo' => [
                'value' => '',
                'type' => 'string',
                'description' => 'School logo file path',
                'is_public' => true,
            ],
            'school_colors' => [
                'value' => json_encode(['primary' => '#007bff', 'secondary' => '#6c757d']),
                'type' => 'json',
                'description' => 'School brand colors',
                'is_public' => true,
            ],
        ];
    }

    /**
     * Create default configurations for school
     */
    public static function createDefaultsForSchool(int $schoolId): void
    {
        $defaults = self::getDefaultConfigurations();
        
        foreach ($defaults as $key => $config) {
            self::setForSchool(
                $schoolId,
                $key,
                $config['value'],
                $config['type'],
                $config['description'],
                $config['is_public']
            );
        }
    }

    /**
     * Delete configuration for school
     */
    public static function deleteForSchool(int $schoolId, string $key): bool
    {
        return self::forSchool($schoolId)->byKey($key)->delete() > 0;
    }
}
