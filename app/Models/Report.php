<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_FOR_REVISION = 'for_revision';

    protected $fillable = [
        'user_id',
        'assigned_provincial_head_id',
        'file_name',
        'file_path',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'review_comment',
        'is_hidden_from_staff_dashboard',
        'is_hidden_from_staff_index',
        'is_hidden_from_admin_dashboard',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function assignedProvincialHead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_provincial_head_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(ReportEntry::class);
    }

    public function submit(?int $assignedProvincialHeadId = null): void
    {
        $this->forceFill([
            'status' => self::STATUS_PENDING,
            'assigned_provincial_head_id' => $assignedProvincialHeadId,
            'submitted_at' => $this->submitted_at ?? now(),
            'reviewed_at' => null,
            'reviewed_by' => null,
            'review_comment' => null,
        ])->save();
    }

    public function markAsReviewed(string $status, int $reviewerId, ?string $comment = null): void
    {
        $this->forceFill([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'review_comment' => $status === self::STATUS_FOR_REVISION ? $comment : null,
        ])->save();
    }

    public function canExport()
{
    return in_array($this->status, ['approved', 'draft']);
}
    
}
