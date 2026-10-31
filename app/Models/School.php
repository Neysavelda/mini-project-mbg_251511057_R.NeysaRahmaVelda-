<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = ['name', 'district'];

    public function distributions(): HasMany
    {
        return $this->hasMany(MealDistribution::class);
    }

    public function monitors(): HasMany
    {
        return $this->hasMany(User::class);
    }
}