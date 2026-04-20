<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficeReminderSchedule extends Model
{
    protected $fillable = [
        'office',
        'message',
        'send_time',
        'is_enabled',
        'last_sent_on',
        'created_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'last_sent_on' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(OfficeReminder::class, 'office_reminder_schedule_id');
    }
}
