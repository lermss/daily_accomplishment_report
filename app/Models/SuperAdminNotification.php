<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SuperAdminNotification extends Model
{
    public const TYPE_URGENT = 'URGENT';
    public const TYPE_REVIEW = 'REVIEW';
    public const TYPE_INFO = 'INFO';

    protected $fillable = [
        'source_key',
        'title',
        'message',
        'type',
        'read_status',
        'read_at',
        'action_label',
        'action_url',
        'meta',
    ];

    protected $casts = [
        'read_status' => 'boolean',
        'read_at' => 'datetime',
        'meta' => 'array',
    ];

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('read_status', false);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }
}
