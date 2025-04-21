<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasCompany
{

    public static function bootHasCompany(): void
    {
        // if (auth()->check()) {

        static::creating(function ($model) {
            $model->company_id = auth()->user()->current_company_id;
        });
        // }
    }

    public function scopeEnsureCompany(Builder $query): Builder
    {
        // if (auth()->check()) {
        return $query->where('company_id', auth()->user()->current_company_id);
        // }
    }

    public function isOnSameCompany(): bool
    {
        // if (auth()->check()) {
        return $this->company_id === auth()->user()->current_company_id;
        // }
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
