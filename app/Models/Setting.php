<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'category',
    ];

    protected $casts = [
        'value' => 'string',
        'type' => 'string',
        'description' => 'string',
        'category' => 'string',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? static::castValue($setting->value, $setting->type) : $default;
    }

    public static function set(string $key, $value, string $type = 'string', string $description = null, string $category = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => gettype($value),
                'description' => $description,
                'category' => $category,
            ]
        );
    }

    public static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'array' => json_decode($value, true),
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    public static function getByCategory(string $category): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('category', $category)->get();
    }
}
