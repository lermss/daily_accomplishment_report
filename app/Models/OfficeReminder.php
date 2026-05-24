<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficeReminder extends Model
{
    public const TYPE_MANUAL = 'manual';
    public const TYPE_SCHEDULED = 'scheduled';

    protected $fillable = [
        'office',
        'message',
        'type',
        'triggered_at',
        'created_by',
        'office_reminder_schedule_id',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(OfficeReminderSchedule::class, 'office_reminder_schedule_id');
    }
}
