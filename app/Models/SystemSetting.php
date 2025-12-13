<?php
// app/Models/SystemSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
        'updated_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get the user who last updated the setting
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for public settings
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope by group
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get setting value with proper casting
     */
    public function getValueAttribute($value)
    {
        return match($this->type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Set setting value with proper encoding
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match($this->type) {
            'array' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };
    }

    /**
     * Helper method to get a setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Helper method to set a setting value
     */
    public static function setValue($key, $value, $type = 'string', $group = 'general', $description = null, $isPublic = false)
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->fill([
            'value' => $value,
            'type' => $type,
            'group' => $group,
            'description' => $description,
            'is_public' => $isPublic,
        ]);
        $setting->save();
        return $setting;
    }
}