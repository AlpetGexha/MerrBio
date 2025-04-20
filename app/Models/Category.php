<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
    ];

    public function getNameAttribute($value)
    {
        if (is_string($value)) {
            return $value;
        }
        $value = json_decode($value, true);
        $locale = app()->getLocale();

        // Try to get the value in the current locale
        if (isset($value[$locale])) {
            return $value[$locale];
        }

        // If not found, try English
        if (isset($value['en'])) {
            return $value['en'];
        }

        // If still not found, return the first available value
        return array_values($value)[0] ?? '';
    }

    public function getDescriptionAttribute($value)
    {
        if (is_string($value)) {
            return $value;
        }
        $value = json_decode($value, true);
        $locale = app()->getLocale();

        // Try to get the value in the current locale
        if (isset($value[$locale])) {
            return $value[$locale];
        }

        // If not found, try English
        if (isset($value['en'])) {
            return $value['en'];
        }

        // If still not found, return the first available value
        return array_values($value)[0] ?? '';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
