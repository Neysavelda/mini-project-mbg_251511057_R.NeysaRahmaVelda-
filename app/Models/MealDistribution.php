<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealDistribution extends Model
{
    protected $fillable = ['school_id', 'distribution_date', 'menu_description', 'portion_count'];

    protected function casts(): array
    {
        return ['distribution_date' => 'date'];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(IncidentReport::class);
    }
}