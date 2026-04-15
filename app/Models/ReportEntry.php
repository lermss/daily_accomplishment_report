<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportEntry extends Model
{
    protected $fillable = [
        'report_id',
        'start_date',
        'end_date',
        'activity',
        'details',
        'remarks',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
