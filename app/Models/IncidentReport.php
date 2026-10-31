<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncidentReport extends Model
{
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_IN_REVIEW = 'in_review';
    public const STATUS_NEEDS_COMPLETION = 'needs_completion';
    public const STATUS_FLAGGED = 'flagged';
    public const STATUS_FORWARDED = 'forwarded';
    public const STATUS_CLOSED = 'closed';

    public const STATUS_LABELS = [
        self::STATUS_SUBMITTED => 'Dikirim',
        self::STATUS_IN_REVIEW => 'Sedang Ditinjau',
        self::STATUS_NEEDS_COMPLETION => 'Perlu Dilengkapi',
        self::STATUS_FLAGGED => 'Ditandai untuk Diteruskan',
        self::STATUS_FORWARDED => 'Telah Diteruskan',
        self::STATUS_CLOSED => 'Selesai',
    ];

    protected $fillable = ['meal_distribution_id', 'incident_at', 'affected_count', 'description'];

    protected function casts(): array
    {
        return ['incident_at' => 'datetime'];
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(MealDistribution::class, 'meal_distribution_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ReportNote::class);
    }

    public function forwardings(): HasMany
    {
        return $this->hasMany(ReportForwarding::class);
    }
}