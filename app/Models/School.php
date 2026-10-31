<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'district',
    ];

    /**
     * Relasi ke Jadwal Distribusi Makanan (1 to Many)
     */
    public function distributions()
    {
        return $this->hasMany(MealDistribution::class);
    }

    /**
     * Relasi ke Laporan Keluhan melalui Distribusi Makanan (Has Many Through)
     */
    public function reports()
    {
        return $this->hasManyThrough(
            IncidentReport::class,
            MealDistribution::class,
            'school_id',           // Foreign key di tabel meal_distributions
            'meal_distribution_id', // Foreign key di tabel incident_reports
            'id',                   // Local key di tabel schools
            'id'                    // Local key di tabel meal_distributions
        );
    }
}