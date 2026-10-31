<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportForwarding extends Model
{
    protected $fillable = ['recipient_type', 'recipient_name', 'forwarded_at', 'note'];

    protected function casts(): array
    {
        return ['forwarded_at' => 'datetime'];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class, 'incident_report_id');
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}